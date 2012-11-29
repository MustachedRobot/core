<?php

namespace Fuel\Migrations;

class Start
{

    function up()
    {
        \DBUtil::create_table('checkins', array(
            'id' => array('type' => 'int', 'constraint' => 5),
            'user_id' => array('type' => 'varchar', 'constraint' => 100),
            'created_at' => array('type' => 'text'),
            'updated_at' => array('type' => 'text'),
            'public' => array('type' => 'text'),
            'reason_id' => array('type' => 'text'),
            'killed' => array('type' => 'text'),
            'count' => array('type' => 'text'),
        ), array('id'));

        
    }

    function down()
    {
       \DBUtil::drop_table('posts');
    }
}