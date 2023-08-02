# Phar Converter

Phar Converter is a simple PHP script that allows you to convert between PHAR and ZIP formats. It provides an easy way to convert your PHP Archive (PHAR) files into ZIP archives and vice versa.

## Features

- Convert PHAR files to ZIP format
- Convert ZIP files to PHAR format
- User-friendly command-line interface
- Lightweight and easy to use

## Requirements

- PHP 8.0 or higher
- [Phar](https://www.php.net/manual/en/book.phar.php) and [ZipArchive](https://www.php.net/manual/en/class.ziparchive.php) extensions enabled

## Installation

1. Open a terminal or command prompt.
2. Run the following command to clone the Phar Converter repository:

   ```shell
   git clone https://github.com/Amitminer/phar-converter.git
   ```

3. Navigate to the `phar-converter` directory:

   ```shell
   cd phar-converter
   ```

4. Run the Phar Converter script to convert files:

   To convert a PHAR file to ZIP format:

   ```shell
   php converter.php input_file.phar output_file.zip
   ```

   To convert a ZIP file to PHAR format:

   ```shell
   php converter.php input_file.zip output_file.phar
   ```

Make sure you have PHP 7.0 or higher and the Phar and ZipArchive extensions enabled.

## Example

Convert a PHAR file named `example.phar` to ZIP format:

```shell
php converter.php example.phar example.zip
```

Convert a ZIP file named `example.zip` to PHAR format:

```shell
php converter.php example.zip example.phar
```

## Acknowledgements

Phar Converter is inspired by the need for a simple tool to convert between PHAR and ZIP formats quickly.

## Contributing

If you find a bug or want to suggest an improvement, please feel free to open an issue or submit a pull request. Your contributions are greatly appreciated!

## License

Phar-Converter is open-source software licensed under the [MIT License](LICENSE).

## Credits

The Phar-Converter is created and maintained by [AmitxD](https://github.com/Amitminer).
