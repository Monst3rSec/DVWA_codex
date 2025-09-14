<?php

// Hardened ping functionality to prevent OS command injection.
if( isset( $_POST[ 'Submit' ] ) ) {
	// Get input
	$target = $_REQUEST[ 'ip' ];

	// Block shell metacharacters outright
	if( preg_match( '/[;|&`$()<>\n\r]/', $target ) ) {
		$html .= '<pre>Invalid input.</pre>';
	}
	// Allow only IPv4 addresses or hostnames (letters, digits, dots, hyphens)
	elseif( ! ( filter_var( $target, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) ||
		preg_match( '/^[A-Za-z0-9.-]+$/', $target ) ) ) {
		$html .= '<pre>Invalid input.</pre>';
	}
	else {
		// Build safe command using fixed binary and escaped argument
		$cmd = '/bin/ping -c 3 -W 2 ' . escapeshellarg( $target );
		$output = shell_exec( $cmd );

		if( $output !== null ) {
			$lines = explode( "\n", $output );
			$safe  = array();

			// Return only typical ping result lines
			foreach( $lines as $line ) {
				if( preg_match( '/^[A-Za-z0-9\s\.:,()=%-]+$/', $line ) ) {
					$safe[] = $line;
				}
			}

			$html .= '<pre>' . implode( "\n", $safe ) . '</pre>';
		}
		else {
			// Do not expose raw shell output on failure
			$html .= '<pre>Ping failed.</pre>';
		}
	}
}

?>
