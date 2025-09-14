# Command Execution low.php hardening

The original `low.php` page concatenated user-supplied input directly into
shell commands. Attackers could inject additional operations by using shell
metacharacters such as `;` or `&&`.

The new implementation:

- validates that the target is a valid IPv4 address or hostname containing only
  letters, digits, dots and hyphens;
- rejects common shell metacharacters;
- builds the command using a fixed `/bin/ping` binary and escapes the argument;
- limits execution to three packets with a short timeout;
- displays only sanitised ping output and hides any underlying shell errors.

This removes the command injection vulnerability while keeping the intended
ping functionality.
