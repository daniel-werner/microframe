<?php
use PHPUnit\Framework\TestCase;
use Models\Test;

class ModelTest extends TestCase
{
    public function testBuildSelect()
    {
        $test = new Test();

        $conditions = [
            'id' => 1
        ];

        $select = $test->buildSelectStatement($conditions);
        $this->assertSame($select, 'SELECT `id`,`name` FROM tests WHERE `id` = 1');
    }

    public function testBuildInsert()
    {
        $data = [
            'name' => 'This is name'
        ];

        $test = new Test($data);

        $insert = $test->buildInsertStatement();
        $this->assertSame($insert, "INSERT INTO tests (`name`) VALUES ('This is name')");

    }

    public function testInsertSelect()
    {
        $data = [
            'name' => 'This is name'
        ];

        $test = new Test($data);

        $test = $test->save();
        $id = $test->id;
        $this->assertTrue( !empty($id) );

        $test2 = new Test();

        $results = $test2->get(['id' => $id]);
        $this->assertTrue($results[0]->id == $id);
    }

}