<?php

/**
 * Shift left (<<)
 * @link http://www.php.net/manual/en/ref.gmp.php#99788
 */
function gmp_shiftl($x, $n)
{
	return gmp_mul($x, gmp_pow('2', $n));
}

/**
 * Shift right (>>)
 * @link http://www.php.net/manual/en/ref.gmp.php#99788
 */
function gmp_shiftr($x, $n)
{
	return gmp_div($x, gmp_pow('2', $n));
}

class IPv6Block extends IPBlock
{
	const IP_VERSION = 6;
	const MAX_BITS = 128;

	/**
	 * Return netmask
	 */
	public function getMask()
	{
		if ( $this->prefix == 0 ) {
			return new IPv6(0);
		}
		$max_int = gmp_init(IPv6::MAX_INT);
		$mask = gmp_shiftl($max_int, self::MAX_BITS - $this->prefix);
		$mask = gmp_and($mask, $max_int); // truncate to 128 bits only
		return new IPv6($mask);
	}

	/**
	 * Return delta to last IP address
	 */
	public function getDelta()
	{
		if ( $this->prefix == 0 ) {
			return new IPv6(IPv6::MAX_INT);
		}
		return new IPv6(gmp_sub(gmp_shiftl(1, self::MAX_BITS - $this->prefix),1));
	}
}