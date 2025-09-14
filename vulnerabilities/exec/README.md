# Command Injection Module

The low-level example has been hardened to prevent OS command injection.
User-supplied input is validated as an IPv4 address or hostname and
sanitized before being passed to `/bin/ping`. Shell metacharacters are
rejected and only standard ping output is returned to the user.
