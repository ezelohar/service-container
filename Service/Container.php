<?php
/**
 * This class works as a service container for manaing providers
 * User: ezelohar
 * Date: 8/30/15
 * Time: 7:10 PM
 */

namespace Service;

/**
 * Class Container
 * @package Service
 */
class Container implements \ArrayAccess
{

	# strings/arrays/scalars/integers/numbers/objects/booleans
	private $_values = array();

	# closures
	private $_closures;

	# all keys
	private $_keys = array();

	# singleton keys
	private $_singletonKeys = array();

	# singleton instances
	private $_instances = array();

	/**
	 * Used when working with class as Array to set data
	 * @param mixed $name | Unique name for provider
	 * @param mixed $provider | Provider
	 */
	public function offsetSet($name, $provider)
	{
		$this->set($name, $provider);
	}

	/**
	 * Returns data if Container accessed as array
	 * @param mixed $name | Unique name for provider
	 * @return mixed | provider value/object/function etc
	 */
	public function offsetGet($name)
	{
		return $this->get($name);
	}

	/**
	 * Delete array key in Container class
	 * @param mixed $name | Unique name for provider
	 */
	public function offsetUnset($name)
	{
		if (isset($this->_keys[$name])) {
			if (!$this->is_closure($name)) {
				unset($this->_values[$name]);
			} else {
				unset($this->_closures[$name]);
			}
			unset($this->_keys[$name]);
		}
	}

	/**
	 * Check if key exists
	 * @param mixed $name | Unique name for provider
	 * @return bool
	 */
	public function offsetExists($name)
	{
		return isset($this->_keys[$name]);
	}


	/**
	 * Main function for setting all providers
	 * @param $name | Unique name for provider
	 * @param $provider |
	 * @param bool|false $singleton | if singleton is set, we have only one instance of given provider, not changeable
	 */
	public function set($name, $provider, $singleton = false)
	{

		# check if name is already set as singleton and if true, don't do anything
		if ($this->is_singleton($name)) {
			return;
		}

		# if provider is a singleton, we keep track about it's name
		if ($singleton) {
			$this->_singletonKeys[$name] = true;
		}

		$this->_keys[$name] = true;

		# if provider is a closure, we store it into different array
		if (method_exists($provider, '__invoke')) {
			$this->_closures[$name] = $provider;
		} else {
			$this->_values[$name] = $provider;
		}
	}

	/**
	 * Get provider based on unique name
	 * @param $name | Unique name for provider
	 * @param null $params | array of params to invoke with closure
	 * @return mixed
	 */
	public function get($name, $params = null)
	{

		if (!isset($this->_keys[$name])) {
			throw new \InvalidArgumentException(sprintf('Key name "%s" not found', $name));
		}


		if (!$this->is_closure($name)) {
			return $this->_values[$name];
		} else {
			# we check to see if there is an instance of required provider.
			# If there is instance, we know it is singleton and return it's value
			if ($this->is_executed($name)) {
				return $this->_instances[$name];
			}

			if ($params != null) {
				# checking to see if function is called with params in array or single param without array
				if (is_array($params)) {
					$func = call_user_func_array($this->_closures[$name], $params);
				} else {
					$func = call_user_func($this->_closures[$name], $params);
				}
			} else {
				$func = $this->_closures[$name]();
			}

			# save instance for future usage if required provider is singleton
			if ($this->is_singleton($name)) {
				$this->_instances[$name] = $func;
			}

			return $func;
		}
	}


	/** Magic Calls */

	/**
	 * Magic call to access inaccessible property from our storage
	 * @param $name | Unique name for provider
	 * @return mixed
	 */
	public function __get($name)
	{
		return $this->get($name);
	}

	/**
	 * Magic call to save inacccessible property to our storage
	 * @param $name | Unique name for provider
	 * @param $provider
	 */
	public function __set($name, $provider)
	{
		$this->set($name, $provider);
	}

	/**
	 * Magic call to access our properties as functions
	 * @param $name
	 * @param $arguments
	 * @return mixed|void
	 */
	public function __call($name, $arguments)
	{
		# we check if provider is not closure, and if arguments are set, we update storage value for given provider
		if (!$this->is_closure($name)) {
			if (!empty($arguments)) {
				if (count($arguments) === 1) {
					return $this->set($name, $arguments[0]);
				} else {
					return $this->set($name, $arguments);
				}

			}
		}

		# otherwise return value
		return $this->get($name, $arguments);
	}


	/* private functions */

	/**
	 * Check if provider is an closure
	 * @param $name | Unique name for provider
	 * @return bool
	 */
	private function is_closure($name)
	{
		return isset($this->_closures[$name]);
	}

	/**
	 * Check if provider is a singleton
	 * @param $name | Unique name for provider
	 * @return bool
	 */
	private function is_singleton($name)
	{
		return isset($this->_singletonKeys[$name]);
	}

	/**
	 * check if provider is executed (for singletons
	 * @param $name | Unique name for provider
	 * @return bool
	 */
	private function is_executed($name)
	{
		return isset($this->_instances[$name]);
	}

	/**
	 * Used to register other providers
	 * @param ServiceInterfaceProvider $provider
	 * @param array $values
	 * @return $this
	 */
	public function register(ServiceInterfaceProvider $provider, array $values = array())
	{
		$provider->register($this);
		foreach ($values as $key => $value) {
			$this[$key] = $value;
		}
		return $this;
	}
}


