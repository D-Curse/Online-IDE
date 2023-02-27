<?php
    // Get the language and code from the form
    $language = strtolower($_POST['language']);
    $code = $_POST['code'];

    // Generate a random filename for the code
    $random = substr(md5(mt_rand()), 0, 7);
    $filePath = "temp/" . $random . "." . $language;

    // Save the code to a file
    file_put_contents($filePath, $code);

    // Compile and run the code based on the language
    switch ($language) {
        case 'python':
            // Run the Python code and capture the output
            $output = shell_exec("python $filePath 2>&1");
            break;

        case 'node':
            // Rename the file to a .js extension and run the Node.js code
            rename($filePath, $filePath . ".js");
            $output = shell_exec("node $filePath.js 2>&1");
            break;

        case 'php':
            // Run the PHP code and capture the output
            $output = shell_exec("php $filePath 2>&1");
            break;

        case 'c':
            // Compile the C code and capture any errors
            exec("gcc -o $filePath.out $filePath 2>&1", $compilerOutput, $compilerReturn);

            // If there were no compiler errors, run the compiled code and capture the output
            if ($compilerReturn === 0) {
                $output = shell_exec("{$filePath}.out 2>&1");
            } else {
                $output = implode("\n", $compilerOutput);
            }

            break;

        case 'cpp':
            // Compile the C++ code and capture any errors
            exec("g++ -o $filePath.out $filePath 2>&1", $compilerOutput, $compilerReturn);

            // If there were no compiler errors, run the compiled code and capture the output
            if ($compilerReturn === 0) {
                $output = shell_exec("{$filePath}.out 2>&1");
            } else {
                $output = implode("\n", $compilerOutput);
            }

            break;

        default:
            // If the language is not recognized, return an error message
            $output = "Error: Unsupported language: $language";
            break;
    }

    // Return the output to the client
    echo $output;
?>
