<?php
/**
 * @file
 * @author Peter Koerner <peter@mazdermind.de>
 */

/**
 * @class
 * A mighty database conection class based upon PDO
 */
class DBCon extends PDO
{
	/**
	 * list of stored connections by database name
	 * 
	 * @var array of {@see DBCon} objects
	 */
	private static $instances = array();
	
	/**
	 * list of database connection informations
	 * 
	 * associative array with the database connection name as key and another
	 * associative array with the keys 'dsn', 'username' and 'password' as value.
	 */
	private static $config = array();
	
	public static function setConfig($config) {
		self::$config = $config;
	}
	
	/**
	 * get an instance to one of the pre-configures databases
	 * 
	 * if there have not been a connection to the requested database
	 * before, a new one will be opened, otherwise the existing connection
	 * will be passed returned. if $forceNew is set, the function will 
	 * allways create a new connection. This forced-new connection won't be 
	 * stored for later calls.
	 * 
	 * the object returned is of class DBCon and therefore also a PDO-object, 
	 * but it is enhanced with common shorthand-functions, PDO is lacking.
	 * 
	 * @param string $database name of the preconfigured database 
	 * 		(see dbconf.php for the actual database config)
	 * 
	 * @param bool $forceNew don't use a cached instance but create a vanilla
	 * 		(new) connection.
	 * 
	 * @return DBCon database connection class
	 */
	public static function getInstance($database = 'main', $forceNew = false) {
		if(!$forceNew && isset(DBCon::$instances[$database]))
			return DBCon::$instances[$database];
		
		try {
			if(!isset(self::$config[$database]))
				error_log('a database connection "'.$database.'" is not configured!');
			
			$instance = new DBCon(self::$config[$database]['dsn'],
				self::$config[$database]['username'],
				self::$config[$database]['password']
			);
			
			$instance->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('DBConStatement', array($instance)));
			$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch (PDOException $e) {
			error_log('database connection error! '.$e->getMessage());
			return null;
		}
		
		if(!$forceNew)
			DBCon::$instances[$database] = $instance;
		
		return $instance;
	}
	
	/**
	 * quote an array of strings
	 * 
	 * @param $strings array of strings
	 * @return array of quoted strings
	 */
	public function quoteArray($strings)
	{
		return array_map(array($this, 'quote'), (array)$strings);
	}
	
	/**
	 * quote an array of strings and concat it into a list that can be used
	 * for an IN-condition
	 * 
	 * @param $strings array of strings
	 * @return string of quoted strings, concatenated by ,
	 */
	public function concatArray($strings)
	{
		return implode(
			',', 
			$this->quoteArray($strings)
		);
	}
	
	/**
	 * build a value-set for a multi-value insert statement
	 * 
	 * @param array $values
	 */
	public function buildValueList($values)
	{
		return '('.implode(', ', $this->quoteArray($values)).')';
	}
	
	/**
	 * try to begin a transaction
	 * 
	 * @return true if the transaction was started, false if not
	 */
	public function tryBeginTransaction()
	{
		// try to begin the transaction
		try
		{
			// cal lthe beginTransaction method
			$this->beginTransaction();
			
			// if did not throw an exception, so return true
			return true;
		}
		
		// catch exaptions
		catch(PDOException $e)
		{
			// return false as the transaction faild to start
			return false;
		}
	}
	
	/**
	 * Execute the specified query and fetch all rows. use the column $key 
	 * as the key and the column $value as the value to build an associative 
	 * array by default the first and the second columns are used as key and 
	 * value.
	 * 
	 * @param string sql querystring
	 * @param int|string $key the field index or name that should be used 
	 *	as key for the result-array
	 * 
	 * @param int|string $value the field index or name that should be used 
	 *	as value for the result-array
	 * 
	 * @return array fetched array, arranged in an associative manner
	 */
	public function queryAssoc($qs, $key = 0, $value = 1, $params = null)
	{
		if($params) {
			$res = $this->prepare($qs);
			$res->execute($params);
		} else {
			$res = $this->query($qs);
		}
		return $res->fetchAssoc($key, $value);
	}
	
	/**
	 * Execute the specified query and fetch all rows. use the column $key 
	 * as the key and the complete column-set (including $key) as the value 
	 * to build an associative array. by default the first column is used as key.
	 * 
	 * @param string sql querystring
	 * @param null|string $key the field index or name that should be used 
	 *	as key for the result-array
	 * 
	 * @return array fetched array, arranged in an associative manner
	 */
	public function queryAssocFull($qs, $key = null, $params = null)
	{
		if($params) {
			$res = $this->prepare($qs);
			$res->execute($params);
		} else {
			$res = $this->query($qs);
		}
		return $res->fetchAssocFull($key);
	}
	
	/**
	 * Execute the specified query, fetch the value from the first column 
	 * of each row of the result set into an array.
	 * 
	 * @param string sql querystring
	 * @param null|array dake sql as prepared statement string and apply using this params
	 * @return array fetched array with values of first column of result
	 */
	public function queryCol($qs, $params = null) {
		if($params) {
			$res = $this->prepare($qs);
			$res->execute($params);
		} else {
			$res = $this->query($qs);
		}
		$res->setFetchMode(PDO::FETCH_COLUMN, 0);
		if(!$res) return array();
		return $res->fetchAll();
	}
	
	/**
	 * Execute the specified query, fetch all the rows of the result set 
	 * into a two dimensional array..
	 * 
	 * @param string sql querystring
	 * @param null|array dake sql as prepared statement string and apply using this params
	 * @return array fetched result 
	 */
	public function queryAll($qs, $params = null) {
		if($params) {
			$res = $this->prepare($qs);
			$res->execute($params);
		} else {
			$res = $this->query($qs);
		}
		if(!$res) return array();
		return $res->fetchAll();
	}

	/**
	 * @param string sql querystring
	 * @param null|array dake sql as prepared statement string and apply using this params
	 * @return array first fetched result row 
	 */
	public function queryRow($qs, $params = null) {
		if($params) {
			$res = $this->prepare($qs);
			$res->execute($params);
		} else {
			$res = $this->query($qs);
		}
		if(!$res) return array();
		return $res->fetch();
	}

	/**
	 * Execute the specified query, fetch the value from the first column 
	 * of the first row of the result set.
	 * 
	 * @param string sql querystring
	 * @param null|array dake sql as prepared statement string and apply using this params
	 * @return mixed value of the first column of the first row
	 */
	public function queryOne($qs, $params = null) {
		if($params) {
			$res = $this->prepare($qs);
			$res->execute($params);
		} else {
			$res = $this->query($qs);
		}
		$res->setFetchMode(PDO::FETCH_COLUMN, 0);
		if(!$res) return null;
		return $res->fetch();
	}
}

// see http://daveyshafik.com/archives/605-debugging-pdo-prepared-statements.html
class DBConStatement extends PDOStatement {
	protected function __construct(PDO $connection)
	{
		$this->connection = $connection;
		$this->setFetchMode(PDO::FETCH_ASSOC);
	}
	
	/**
	 * override the original PDOStatement::execute to allow typed parameter 
	 * binding
	 * 
	 * @param array $input_parameters An array of values with as many elements as there are bound parameters in the SQL statement being executed. All values are treated as PDO::PARAM_STR.
	 * @return Returns TRUE on success or FALSE on failure.
	 */
	public function execute($input_parameters = array())
	{
		foreach($input_parameters as $k => $v)
		{
			// first analyze the type of the value to 
			// choose the right PDO::PARAM-Type
			switch(true)
			{
				// is_bool -> PDO::PARAM_BOOL
				case is_bool($v):
					$t = PDO::PARAM_BOOL;
					break;
					
				// is_float & is_numeric -> PDO::PARAM_STR
				case is_numeric($v):
				case is_int($v):
					$t = PDO::PARAM_STR;
					break;
				
				// is_int -> PDO::PARAM_INT
				case is_int($v):
					$t = PDO::PARAM_INT;
					break;
				
				// is_null -> PDO::PARAM_NULL
				case is_null($v):
					$t = PDO::PARAM_NULL;
					break;
				
				// default -> PDO::PARAM_STR
				default:
					$t = PDO::PARAM_STR;
					break;
			}
			
			// numeric keys are 1-based in mysql, so we have to increment them
			if(is_int($k)) $k++;
			
			// now bind the variable value using the choosen type hint
			$this->bindValue($k, $v, $t);
		}
		
		// not let the parent do the execution and return the result
		return parent::execute();
	}
	
	/**
	 * execute a prepared statement using the supplied parameters and return 
	 * all rows
	 * 
	 * @param array $input_parameters the parameters to insert into the statement, 
	 *	see PDOStatement::execute()
	 * 
	 * @return array all rows
	 */
	public function executeAll($input_parameters = array())
	{
		$this->execute($input_parameters);
		return $this->fetchAll();
	}
	
	/**
	 * execute a prepared statement using the supplied parameters and return 
	 * the value of the first column of the first row
	 * 
	 * @param array $input_parameters the parameters to insert into the statement, 
	 *	see PDOStatement::execute()
	 * @param int $column_number select a specific column of the row, whereas 0 is 
	 *	the default and fetches the first column
	 * 
	 * @return string the value of the first column of the first row, returned by 
	 *	the execution of this prepared statement
	 */
	public function executeOne($input_parameters = array(), $column_number = 0)
	{
		$this->execute($input_parameters);
		return $this->fetchOne($column_number);
	}
	
	/**
	 * fetch the value from the first column of the first row of the result set.
	 * 
	 * @param int $column_number select a specific column of the row, whereas 0 is 
	 *	the default and fetches the first column
	 * 
	 * @return string the value of the first column of the first row, returned by 
	 *	the execution of this prepared statement 
	 */
	public function fetchOne($column_number = 0)
	{
		$c = $this->fetchColumn($column_number);
		return !$c ? null : $c;
	}
	
	/**
	 * execute a prepared statement using the supplied parameters and return 
	 * the value of the first column of each row
	 * 
	 * @param array $input_parameters the parameters to insert into the statement, 
	 *	see PDOStatement::execute()
	 * 
	 * @param int $column_number select a specific column of the row, whereas 0 is
	 *	the default and fetches the first column 
	 *	 
	 * @return array the value of the first column of each row, returned by 
	 *	the execution of this prepared statement
	 */
	public function executeCol($input_parameters = array(), $column_number = 0)
	{
		$this->execute($input_parameters);
		return $this->fetchCol($column_number);
	}
	
	/**
	 * fetch the value from the first column of the first row of the result 
	 * set into an array.
	 * 
	 * @param int $column_number select a specific column of the row, whereas 0 is 
	 *	the default and fetches the first column
	 * 
	 * @return string the value of the first column of the first row, returned by 
	 *				the execution of this prepared statement 
	 */
	public function fetchCol($column_number = 0)
	{
		$col = array();
		while($value = $this->fetchColumn($column_number))
			$col[] = $value;
		
		return $col;
	}
	
	/**
	 * execute a prepared statement using the supplied parameters and fetch 
	 * all rows. use the column $key as the key and the column $value as the 
	 * value to build an associative array by default the first and the second 
	 * columns are used as key and value.
	 * 
	 * @param array $input_parameters the parameters to insert into the statement, 
	 *	see PDOStatement::execute()
	 * 
	 * @param int|string $key the field index or name that should be used 
	 *	as key for the result-array
	 * 
	 * @param int|string $value the field index or name that should be used 
	 *	as value for the result-array
	 * 
	 * @return array fetched array, arranged in an associative manner
	 */
	public function executeAssoc($input_parameters = array(), $key = 0, $value = 1)
	{
		$this->execute($input_parameters);
		return $this->fetchAssoc($key, $value);
	}
	
	/**
	 * fetch all rows. use the column $key as the key and the column $value 
	 * as the value to build an associative array by default the first and 
	 * the second columns are used as key and value.
	 * 
	 * @param int|string $key the field index or name that should be used 
	 *	as key for the result-array
	 * 
	 * @param int|string $value the field index or name that should be used 
	 *	as value for the result-array
	 * 
	 * @return array fetched array, arranged in an associative manner
	 */
	public function fetchAssoc($key = 0, $value = 1)
	{
		$this->setFetchMode(PDO::FETCH_BOTH);
		
		$a = array();
		while($row = $this->fetch())
		{
			$a[$row[$key]] = $row[$value];
		}
		
		return $a;
	}
	
	/**
	 * execute a prepared statement using the supplied parameters and fetch 
	 * all rows. use the column $key as the key and the complete column-set 
	 * (including $key) as the value to build an associative array by default 
	 * the first column is used as key
	 * 
	 * @param array $input_parameters the parameters to insert into the statement, 
	 *	see PDOStatement::execute()
	 * 
	 * @param null|string $key the field index or name that should be used 
	 *	as key for the result-array
	 * 
	 * @return array fetched array, arranged in an associative manner
	 */
	public function executeAssocFull($input_parameters, $key = null)
	{
		$this->execute($input_parameters);
		return $this->fetchAssocFull($key);
	}
	
	/**
	 * fetch all rows. use the column $key as the key and the complete column-set 
	 * (including $key) as the value to build an associative array by default 
	 * the first column is used as key
	 * 
	 * @param null|string $key the field index or name that should be used 
	 *	as key for the result-array
	 * 
	 * @return array fetched array, arranged in an associative manner
	 */
	public function fetchAssocFull($key = null)
	{
		$a = array();
		while($row = $this->fetch())
		{
			// no key column has been specified
			// find the first column name in the set
			if(is_null($key))
				$key = reset(array_keys($row));
			
			$a[$row[$key]] = $row;
		}
		
		return $a;
	}
	
	/**
	 * execute a prepared statement using the supplied parameters and fetch 
	 * the first row.
	 * 
	 * @param array $input_parameters the parameters to insert into the statement, 
	 *	see PDOStatement::execute()
	 * 
	 * @return array first row of the result set
	 */
	public function executeRow($input_parameters)
	{
		$this->execute($input_parameters);
		return $this->fetch();
	}
}

/**
 * shorty for DBCon::getInstance($database)
 * 
 * @param $database database connection to fetch a DBCon-object for
 * @return DBCon object
 */
if(!function_exists('db'))
{
	function db($database = 'main')
	{
		return DBCon::getInstance($database);
	}
}

?>