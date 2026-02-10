<?php

namespace App\Filters;

use App\Models\UserPermissionModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        // login yapmamışsa
        $user = $session->get('user');
        if (!$user) {
            return redirect()->to(base_url('login'));
        }

        $role   = is_array($user) ? (string)($user['role'] ?? '') : '';
        $userId = is_array($user) ? (string)($user['id'] ?? ($user['user_id'] ?? '')) : '';

        if ($userId === '' || $role === '') {
           return redirect()->to(base_url('login'));
        }

        // Argüman parse: ör. ['admin|perm:manage_products'] veya ['admin,secretary']
        $allowedRoles = [];
        $requiredPerm = null;

        if (!empty($arguments)) {
            $flat  = implode(',', $arguments);
            $parts = preg_split('/\|/', $flat);

            foreach ($parts as $p) {
                $p = trim($p);

                // "role:admin" yazarsan diye tolerans ekleyelim
                if (str_starts_with($p, 'role:')) {
                    $p = trim(substr($p, 5));
                }

                if (str_starts_with($p, 'perm:')) {
                    $requiredPerm = trim(substr($p, 5));
                    continue;
                }

                foreach (explode(',', $p) as $r) {
                    $r = trim($r);
                    if ($r !== '') {
                        $allowedRoles[] = $r;
                    }
                }
            }
        }

        // Role check
        if (!empty($allowedRoles) && !in_array($role, $allowedRoles, true)) {
            return $this->deny($request);
        }

        // Permission check (opsiyonel)
        if (!empty($requiredPerm)) {

            // Admin her şeyi geçsin
            if ($role === 'admin') {
                return null;
            }

            $upm = new UserPermissionModel();

            // UUID string gönder
            $allowed = $upm->isAllowed($userId, $requiredPerm);

            if (!$allowed) {
                return $this->deny($request);
            }
        }

    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // no-op
    }

    private function deny(RequestInterface $request)
    {
        $accept = strtolower($request->getHeaderLine('Accept'));
        $xrw    = strtolower($request->getHeaderLine('X-Requested-With'));

        $wantsJson =
            str_contains($accept, 'application/json') ||
            $xrw === 'xmlhttprequest';

        if ($wantsJson) {
            return service('response')
                ->setStatusCode(403)
                ->setJSON(['error' => 'forbidden']);
        }

        return service('response')->setStatusCode(403, 'Forbidden');
    }
}