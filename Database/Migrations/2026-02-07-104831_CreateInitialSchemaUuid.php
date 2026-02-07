<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInitialSchemaUuid extends Migration
{
    public function up()
    {
        // USERS
        $this->forge->addField([
            'id' => ['type' => 'CHAR', 'constraint' => 36],
            'username' => ['type' => 'VARCHAR', 'constraint' => 100],
            'email' => ['type' => 'VARCHAR', 'constraint' => 100],
            'password' => ['type' => 'VARCHAR', 'constraint' => 255],
            'role' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'user'], // admin|secretary|user
            'status' => ['type' => 'VARCHAR', 'constraint' => 30, 'default' => 'active'], // pending_verification|active|suspended|banned
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->addKey('role', false, false, 'idx_users_role');
        $this->forge->addKey('status', false, false, 'idx_users_status');
        $this->forge->createTable('users', true);

        // AUTHORS
        $this->forge->addField([
            'id' => ['type' => 'CHAR', 'constraint' => 36],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'bio' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('authors', true);

        // CATEGORIES
        $this->forge->addField([
            'id' => ['type' => 'CHAR', 'constraint' => 36],
            'category_name' => ['type' => 'VARCHAR', 'constraint' => 255],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('categories', true);

        // TYPES
        $this->forge->addField([
            'id' => ['type' => 'CHAR', 'constraint' => 36],
            'name' => ['type' => 'VARCHAR', 'constraint' => 255],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('types', true);

        // PRODUCTS
        $this->forge->addField([
            'id' => ['type' => 'CHAR', 'constraint' => 36],

            'author_id' => ['type' => 'CHAR', 'constraint' => 36, 'null' => true],
            'type_id' => ['type' => 'CHAR', 'constraint' => 36, 'null' => true],
            'category_id' => ['type' => 'CHAR', 'constraint' => 36, 'null' => true],

            'product_name' => ['type' => 'VARCHAR', 'constraint' => 255],
            'author' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'description' => ['type' => 'TEXT', 'null' => true],
            'price' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'stock_count' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'type' => ['type' => 'VARCHAR', 'constraint' => 20, 'default' => 'basili'], // enum yerine string
            'image' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'stock' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],

            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('author_id', false, false, 'idx_products_author_id');
        $this->forge->addKey('type_id', false, false, 'idx_products_type_id');
        $this->forge->addKey('category_id', false, false, 'idx_products_category_id');
        $this->forge->createTable('products', true);

        // FK'ler (products)
        $this->db->query("ALTER TABLE products
            ADD CONSTRAINT fk_products_author FOREIGN KEY (author_id) REFERENCES authors(id)
            ON DELETE SET NULL ON UPDATE CASCADE");
        $this->db->query("ALTER TABLE products
            ADD CONSTRAINT fk_products_type FOREIGN KEY (type_id) REFERENCES types(id)
            ON DELETE SET NULL ON UPDATE CASCADE");
        $this->db->query("ALTER TABLE products
            ADD CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id)
            ON DELETE SET NULL ON UPDATE CASCADE");

        // ORDERS
        $this->forge->addField([
            'id' => ['type' => 'CHAR', 'constraint' => 36],
            'product_id' => ['type' => 'CHAR', 'constraint' => 36],
            'quantity' => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
            'total_price' => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'order_date' => ['type' => 'DATETIME', 'null' => true],
            
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('product_id', false, false, 'idx_orders_product_id');
        $this->forge->createTable('orders', true);

        $this->db->query("ALTER TABLE orders
            ADD CONSTRAINT fk_orders_product FOREIGN KEY (product_id) REFERENCES products(id)
            ON DELETE RESTRICT ON UPDATE RESTRICT");

        // RBAC: ROLES
        $this->forge->addField([
            'id' => ['type' => 'CHAR', 'constraint' => 36],
            'name' => ['type' => 'VARCHAR', 'constraint' => 50],
            'description' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],

            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('name');
        $this->forge->createTable('roles', true);

        // RBAC: PERMISSIONS
        $this->forge->addField([
            'id' => ['type' => 'CHAR', 'constraint' => 36],
            'code' => ['type' => 'VARCHAR', 'constraint' => 100],
            'description' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],

            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('code');
        $this->forge->createTable('permissions', true);

        // role_permissions (pivot)
        $this->forge->addField([
            'id' => ['type' => 'CHAR', 'constraint' => 36],
            'role_id' => ['type' => 'CHAR', 'constraint' => 36],
            'permission_id' => ['type' => 'CHAR', 'constraint' => 36],

            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['role_id','permission_id'], false, true, 'uq_role_permissions_pair');
        $this->forge->createTable('role_permissions', true);

        $this->db->query("ALTER TABLE role_permissions
            ADD CONSTRAINT fk_rp_role FOREIGN KEY (role_id) REFERENCES roles(id)
            ON DELETE CASCADE ON UPDATE CASCADE");
        $this->db->query("ALTER TABLE role_permissions
            ADD CONSTRAINT fk_rp_permission FOREIGN KEY (permission_id) REFERENCES permissions(id)
            ON DELETE CASCADE ON UPDATE CASCADE");

        // user_permissions (override)
        $this->forge->addField([
            'id' => ['type' => 'CHAR', 'constraint' => 36],
            'user_id' => ['type' => 'CHAR', 'constraint' => 36],
            'permission_id' => ['type' => 'CHAR', 'constraint' => 36],
            'allowed' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],

            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['user_id','permission_id'], false, true, 'uq_user_permissions_pair');
        $this->forge->createTable('user_permissions', true);

        $this->db->query("ALTER TABLE user_permissions
            ADD CONSTRAINT fk_up_user FOREIGN KEY (user_id) REFERENCES users(id)
            ON DELETE CASCADE ON UPDATE CASCADE");
        $this->db->query("ALTER TABLE user_permissions
            ADD CONSTRAINT fk_up_permission FOREIGN KEY (permission_id) REFERENCES permissions(id)
            ON DELETE CASCADE ON UPDATE CASCADE");
    }

    public function down()
    {
        $this->forge->dropTable('user_permissions', true);
        $this->forge->dropTable('role_permissions', true);
        $this->forge->dropTable('permissions', true);
        $this->forge->dropTable('roles', true);
        $this->forge->dropTable('orders', true);
        $this->forge->dropTable('products', true);
        $this->forge->dropTable('types', true);
        $this->forge->dropTable('categories', true);
        $this->forge->dropTable('authors', true);
        $this->forge->dropTable('users', true);
    }
}