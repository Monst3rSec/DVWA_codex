import subprocess


def run_low(ip: str) -> str:
    ip = ip.replace("\"", "\\\"")
    script = (
        '$html=""; '
        '$_POST["Submit"]="1"; '
        '$_REQUEST["ip"]="' + ip + '"; '
        'include "vulnerabilities/exec/source/low.php"; '
        'echo $html;'
    )
    result = subprocess.run(["php", "-r", script], capture_output=True, text=True)
    return result.stdout


def test_valid_ip():
    out = run_low("127.0.0.1")
    assert "PING" in out


def test_reject_injection():
    out = run_low("127.0.0.1; cat /etc/passwd")
    assert "Invalid input" in out
    assert "root:" not in out
