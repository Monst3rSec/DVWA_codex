<?php

if( isset( $_POST[ 'Submit' ]  ) ) {
        // Get input
        $target = $_REQUEST[ 'ip' ];

        // Reject any characters that can be used for command injection
        if ( preg_match( '/[;|&`$()<>]/', $target ) ) {
                $html .= '<pre>Invalid host</pre>';
                return;
        }

        // Allow only IPv4 addresses or hostnames made of letters, digits, dots and hyphens
        if ( !filter_var( $target, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 ) && !preg_match( '/^[A-Za-z0-9.-]+$/', $target ) ) {
                $html .= '<pre>Invalid host</pre>';
                return;
        }

        // Build the command using a fixed path and escape the argument
        $cmd = '/bin/ping -c 3 -W 2 ' . escapeshellarg( $target );
        $output = [];
        $status = 0;

        // Execute the command safely
        exec( $cmd, $output, $status );

        if ( $status === 0 ) {
                // Only display safe ping output back to the user
                $safe_output = htmlspecialchars( implode( "\n", $output ) );
                $html .= "<pre>{$safe_output}</pre>";
        } else {
                // Don't expose raw shell errors
                $html .= '<pre>Ping failed</pre>';
        }
}

?>
