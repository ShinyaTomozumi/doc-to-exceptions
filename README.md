# doc_to_exceptions
This tool creates the source code that manages the exception handling of the program from the documentation.<br>

## Requirement
To use this project, the following environment must be prepared

 - python v3.9

Since this tool needs to read yaml files, the following libraries are used
```commandline
pip install pyyaml
```


## How to use
The usage is as follows.<br>
1. Describe the list of exception handling using "example.yaml" that exists in the project.
2. Use "-i" in the command to specify the file to be read and execute.
3. Incorporate the generated source code into your program.

Example command:<br>
```commandline
python doc_to_exeptions -i example.yaml -project laravel -o output_source
```

## Document specifications
"yaml" is supported for documents written by this tool.
The specification of "yaml" is as follows.
```yaml
## example
version: '1.0' # Document Version
copyright: Sample # Copy right
author: AuthorName # Author name
description: | # Description for document
  Description
type: api # Types of sources to incorporate
exceptions: # Add exception handling below.。
  unknown: # Exception ID. It is written in snake form.
    result: err_unknown # Result code. String.
    code: -1  # Numerical Result Codes.
    message: |  # Message to be returned.
      Unknown error.
    response_code: 400  # In the case of WebAPI, the code here is the response code.
    description: |  # This is an explanation of this exception handling.
      Exception errors when they occur.
```

## Options
The options for the command at runtime will be as follows.

| Option   | Description                                                                                                                        |
|----------|------------------------------------------------------------------------------------------------------------------------------------|
| -i       | Required.<br>The path of the yaml file to be read.                                                                                 |
| -project | Required.<br>The type of project to incorporate the generated source code.<br>The following are currently supported.<br> - laravel |
| -o       | Optional.<br>The path of the output destination.<br>Defaults to the name of the selected project.                                  |
| -doc     | Optional.<br>The type of document to be loaded.<br>The following document types are supported.<br> - yaml                          |


## Note
In a previous project, I designed the exception handling and then created the source code on my own.<br>
However, it took a long time, so we created this tool.<br>
It is possible to create exceptions easily, but it is difficult to find them when investigating exceptions later.<br>
By incorporating the code generated by this tool, it is also faster to find out where the exception was handled.


## Author

* Shinya Tomozumi
* Tomozumi System
* WebSite: https://tomozumi-system.com
* Twitter : https://twitter.com/hincoco27