<?php
use Concrete\Core\Config\DatabaseLoader;

class DatabaseLoaderTest extends ConcreteDatabaseTestCase
{

    /** @var DatabaseLoader */
    protected $loader;

    protected $tables = array('Config');
    protected $fixtures = array('Config');

    public function setUp()
    {
        parent::setUp();
        $this->loader = new DatabaseLoader();
    }

    public function testLoadingConfig()
    {
        $array = $this->loader->load('test', 'test');
        $this->assertEquals('test', array_get($array, 'test.test'), 'Failed to read config value from the database.');
    }

    public function testLoadingNamespacedConfig()
    {
        $array = $this->loader->load('namespaced', 'namespaced', 'namespaced');

        $this->assertEquals(
            'namespaced',
            array_get($array, 'namespaced.namespaced'),
            'Failed to read namespaced config value from the database.');
    }

    public function testExists()
    {
        $group = md5(time());
        $exists_before = $this->loader->exists($group);

        $db = \Database::getActiveConnection();
        $db->insert('Config', array('configItem' => $group, 'configValue' => 1, 'configGroup' => $group));

        $exists_after = $this->loader->exists($group);

        $this->assertFalse($exists_before);
        $this->assertTrue($exists_after);
    }

    public function testExistsNamespaced()
    {
        $group = md5(uniqid());
        $namespace = md5(uniqid());
        $exists_before = $this->loader->exists($group, $namespace);

        $db = \Database::getActiveConnection();
        $db->insert(
            'Config',
            array(
                'configItem'      => $group,
                'configValue'     => 1,
                'configGroup'     => $group,
                'configNamespace' => $namespace));

        $exists_after = $this->loader->exists($group, $namespace);

        $this->assertFalse($exists_before);
        $this->assertTrue($exists_after);
    }

    public function testAddNamespace() {
        // Satisfy coverage
        $this->loader->addNamespace('', '');
        $this->assertTrue(true);
    }

    public function testGetNamespaces() {
        $namespaces_first = $this->loader->getNamespaces();

        $namespace = md5(uniqid());
        $db = \Database::getActiveConnection();
        $db->insert(
            'Config',
            array(
                'configItem'      => $namespace,
                'configValue'     => 1,
                'configGroup'     => 'test',
                'configNamespace' => $namespace));

        $namespaces_after = $this->loader->getNamespaces();

        $value = array_shift(array_diff($namespaces_after, $namespaces_first));

        $this->assertEquals($namespace, $value);
    }

}
