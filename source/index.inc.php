<?php
function fmt_num($number, $decimals = 1) {
	return number_format($number, $decimals, '.', '');
}

function fmt_mb($number, $decimals = 0) {
	return number_format($number/1024, $decimals, '.', '');
}

function read_cpu_temp() {
	# Read ARM CPU temp from file
	$tmp = rtrim(file_get_contents("/sys/class/thermal/thermal_zone0/temp"));

	# Convert to double
	return intval($tmp) * 1e-3;
}

function read_meminfo() {
	$meminfo = array();

	# Read meminfo file
	$handle = fopen("/proc/meminfo", "r");
	if ($handle) {
		# Split each line into key/value
		while (($line = fgets($handle)) !== false) {
			list($key, $val) = explode(":", trim($line));
			$meminfo[$key] = filter_var($val, FILTER_SANITIZE_NUMBER_INT);
		}

		# Close handle
		fclose($handle);
	}

	return $meminfo;
}

function read_network_state($host = "1.1.1.1", $port = 443) {
	$error_code;
	$error_message;
	$timeout = 2;
	if ($connected = @fsockopen($host, $port, $error_code, $error_message, $timeout)) {
		fclose($connected);
		return true;
	}
	return false;
}

class RpiStatus {
	public $cpu_temp;

	public $load_one;
	public $load_five;
	public $load_fifteen;

	public $memory_total;
	public $memory_buffers;
	public $memory_cached;
	public $memory_free;

	public $memory_used;
	public $memory_percent;

	public $memory_used_noncached;
	public $memory_percent_noncached;

	public $net_state;

	function __construct() {
		# Get CPU temp
		$this->cpu_temp = read_cpu_temp();

		# Get load averages
		list($this->load_one, $this->load_five, $this->load_fifteen) = sys_getloadavg();

		# Get memory info
		$meminfo = read_meminfo();
		$this->memory_total = $meminfo["MemTotal"];
		$this->memory_buffers = $meminfo["Buffers"];
		$this->memory_cached = $meminfo["Cached"] + $meminfo["SReclaimable"] - $meminfo["Shmem"];
		$this->memory_free = $meminfo["MemFree"];

		# Calc used cached + non-cached memory
		$this->memory_used = $this->memory_total - $this->memory_free;
		$this->memory_percent = ($this->memory_used / $this->memory_total) * 100;

		# Calc used non-cached memory
		$this->memory_used_noncached = $this->memory_used - ($this->memory_buffers + $this->memory_cached);
		$this->memory_percent_noncached = ($this->memory_used_noncached / $this->memory_total) * 100;

		# Get network state
		$this->net_state = read_network_state();
	}
}
?>
