<?php

namespace Fuel\Migrations;

class Setup
{

    function up()
    {

        \DBUtil::create_table(
            'companies', 
            array(
                'id' => array('type' => 'int', 'unsigned' => true, 'auto_increment' => true, 'constraint' => 11),
                'name' => array('type' => 'varchar', 'constraint' => 120),
            ), 
            array('id'),
            true,
            'InnoDb',
            'utf8_unicode_ci'            
        );

        \DBUtil::create_table(
            'users', 
            array(
                'id' => array('type' => 'int', 'unsigned' => true, 'auto_increment' => true, 'constraint' => 11),
                'username' => array('type' => 'varchar', 'constraint' => 50),
                'password' => array('type' => 'varchar', 'constraint' => 255),
                'group' => array('type' => 'int', 'constraint' => 50, 'default' => 1),
                'email' => array('type' => 'varchar', 'constraint' => 255),
                'firstname' => array('type' => 'varchar', 'constraint' => 100),
                'lastname' => array('type' => 'varchar', 'constraint' => 100),
                'biography' => array('type' => 'text'),
                'twitter' => array('type' => 'varchar', 'constraint' => 100),
                'last_login' => array('type' => 'varchar', 'constraint' => 25),
                'login_hash' => array('type' => 'varchar', 'constraint' => 255),
                'profile_fields' => array('type' => 'text'),                
                'created_at' => array('type' => 'int', 'unsigned' => true),
                'company_id' => array('type' => 'int', 'unsigned' => true, 'constraint' => 11, 'null' => true),
            ), 
            array('id'),
            true,
            'InnoDb',
            'utf8_unicode_ci',
            array(
                array(
                    'key' => 'company_id',
                    'reference' => array(
                        'table' => 'companies',
                        'column' => 'id'
                    ),        
                ),
            )
        );   

        \DBUtil::create_index('users', 'username', 'username', 'unique');     
        \DBUtil::create_index('users', 'email', 'email', 'unique');     


        

        \DBUtil::create_table(
            'reasons', 
            array(
                'id' => array('type' => 'int', 'unsigned' => true, 'auto_increment' => true, 'constraint' => 11),
                'name' => array('type' => 'varchar', 'constraint' => 100),
                'sentence' => array('type' => 'varchar', 'constraint' => 100),
                'order' => array('type' => 'int', 'constraint' => 11),
            ), 
            array('id'),
            true,
            'InnoDb',
            'utf8_unicode_ci'            
        );

        // Insert convention value for coworking
        $query = \DB::insert('reasons')
            ->set(
                array(
                    'name' => 'Coworking',
                    'sentence' => 'est venu coworker',
                    'order' => 1,
                )
            )
            ->execute();

        \DBUtil::create_table(
            'skills', 
            array(
                'id' => array('type' => 'int', 'unsigned' => true, 'auto_increment' => true, 'constraint' => 11),
                'name' => array('type' => 'varchar', 'constraint' => 100),
            ), 
            array('id'),
            true,
            'InnoDb',
            'utf8_unicode_ci'            
        );

        \DBUtil::create_table(
            'skills_users', 
            array(
                'user_id' => array('type' => 'int', 'unsigned' => true, 'constraint' => 11),
                'skill_id' => array('type' => 'int', 'unsigned' => true, 'constraint' => 11),
            ), 
            array('user_id', 'skill_id'),
            true,
            'InnoDb',
            'utf8_unicode_ci',
            array(
                array(
                    'key' => 'user_id',
                    'reference' => array(
                        'table' => 'users',
                        'column' => 'id'
                    ),        
                ),
                array(
                    'key' => 'skill_id',
                    'reference' => array(
                        'table' => 'skills',
                        'column' => 'id'
                    ),        
                ),
            )           
        );        

        \DBUtil::create_table(
            'checkins', 
            array(
                'id' => array('type' => 'int', 'unsigned' => true, 'auto_increment' => true),
                'user_id' => array('type' => 'int', 'unsigned' => true, 'constraint' => 11),
                'created_at' => array('type' => 'timestamp', 'null' => true),
                'updated_at' => array('type' => 'timestamp', 'null' => true),
                'public' => array('type' => 'tinyint', 'constraint' => 4, 'default' => 1),
                'reason_id' => array('type' => 'int', 'unsigned' => true, 'constraint' => 11),
                'killed' => array('type' => 'tinyint', 'null' => true),
                'count' => array('type' => 'int', 'constraint' => 11, 'null' => true),
            ), 
            array('id'),
            true,
            'InnoDb',
            'utf8_unicode_ci',
            array(
                array(
                    'key' => 'user_id',
                    'reference' => array(
                        'table' => 'users',
                        'column' => 'id'
                    ),        
                ),
                array(
                    'key' => 'reason_id',
                    'reference' => array(
                        'table' => 'reasons',
                        'column' => 'id'
                    ),        
                ),
            )
        );
        
    }

    function down()
    {
       \DBUtil::drop_table('skills_users');       
       \DBUtil::drop_table('skills');
       \DBUtil::drop_table('checkins');       
       \DBUtil::drop_table('reasons');
       \DBUtil::drop_table('users');       
       \DBUtil::drop_table('companies');
       
       
       
       
    }
}