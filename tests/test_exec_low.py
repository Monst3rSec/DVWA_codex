import subprocess
import os


def run_low(target: str) -> str:
    script = (
        '$_POST["Submit"]=1; '
        f'$_REQUEST["ip"]="{target}"; '
        'include "vulnerabilities/exec/source/low.php"; '
        'echo $html;'
    )
    result = subprocess.run(
        ["php", "-r", script],
        capture_output=True,
        text=True,
        cwd=os.path.join(os.path.dirname(__file__), ".."),
    )
    return result.stdout


def test_valid_ip():
    out = run_low("127.0.0.1")
    assert "PING" in out


def test_block_metacharacters():
    out = run_low("127.0.0.1; ls")
    assert "Invalid host" in out and "<pre>" in out
