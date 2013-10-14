<?php
namespace Rest;


abstract class Extras
{
    /**
     * Contains the string extensions <br/>
     * Default is array('html')
     * @var \array
     */
    protected $extensions;

    /**
     * Contains the class name and method for the module to be executed before the main controller
     * @var \array
     */
    private $pre_modules;

    /**
     * Contains the class name and method for the module to be executed after the main controller
     * @var \array
     */
    private $post_modules;

    public function __construct()
    {
        $this->extensions = array();
        $this->pre_modules = array();
        $this->post_modules = array();
    }

    /**
     * Add an extension the server is handling
     * @param string $extension
     */
    public function addExtension($extension)
    {
        $this->extensions[$extension] = true;
    }

    /**
     * Remove an extension the server is handling
     * @param string$extension
     */
    public function removeExtension($extension)
    {
        unset($this->extensions[$extension]);
    }

    /**
     * Check if the extension is handle by the server
     * @param string $extension
     * @return bool
     */
    public function hasExtension($extension)
    {
        return array_key_exists($extension, $this->extensions);
    }

    /**
     * Add multiple extensions at once.<br />
     * The array to pass is a indexed array.
     * @param array $extensions
     */
    public function addExtensions($extensions)
    {
        if (is_null($extensions) )
        {
            return;
        }
        foreach( $extensions as $extension )
        {
            $this->extensions[$extension] = true;
        }
    }

    /**
     * Remove all the extensions from the server
     */
    public function clearExtensions()
    {
        $this->extensions = array();
    }

    /**
     * Returns an indexed array (sorting not important) of extensions handle by the server
     * @return array
     */
    public function getExtensions()
    {
        return array_keys($this->extensions);
    }

    /**
     * Add a pre module
     * @param string $module
     */
    public function addPreModule($module)
    {
        $this->pre_modules[$module] = true;
    }

    /**
     * Remove a pre module
     * @param string $module
     */
    public function removePreModule($module)
    {
        unset($this->pre_modules[$module]);
    }

    /**
     * Check if the extension is handle by the server
     * @param string $module
     * @return bool
     */
    public function hasPreModule($module)
    {
        return array_key_exists($module, $this->pre_modules);
    }

    /**
     * Add multiple extensions at once.<br />
     * The array to pass is a indexed array.
     * @param array $modules
     */
    public function addPreModules($modules)
    {
        foreach( $modules as $module )
        {
            $this->pre_modules[$module] = true;
        }
    }

    /**
     * Remove all the extensions from the server
     */
    public function clearPreModules()
    {
        $this->pre_modules = array();
    }

    /**
     * Returns an indexed array (sorting not important) of extensions handle by the server
     * @return array
     */
    public function getPreModules()
    {
        return array_keys($this->pre_modules);
    }
    /**
     * Add a post module
     * @param string $module
     */
    public function addPostModule($module)
    {
        $this->post_modules[$module] = true;
    }

    /**
     * Remove a post module
     * @param string $module
     */
    public function removePostModule($module)
    {
        unset($this->post_modules[$module]);
    }

    /**
     * Check if a modules is in the post modules
     * @param string $module
     * @return bool
     */
    public function hasPostModule($module)
    {
        return array_key_exists($module, $this->post_modules);
    }

    /**
     * Add multiple post modules at once.<br />
     * @param array $modules
     */
    public function addPostModules($modules)
    {
        foreach( $modules as $module )
        {
            $this->post_modules[$module] = true;
        }
    }

    /**
     * Remove all the post modules
     */
    public function clearPostModules()
    {
        $this->post_modules = array();
    }

    /**
     * Returns an indexed array (sorting not important) of post modules
     * @return array
     */
    public function getPostModules()
    {
        return array_keys($this->post_modules);
    }
}
