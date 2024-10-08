<?php

namespace App\Services\Filesystem;

use phpDocumentor\Reflection\Types\String_;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

class WecFilesystem extends Filesystem
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    public function __construct(private string $filesystemBasePath)
    {
        $this->filesystem = new Filesystem();
    }


    /**
     * Copies a file.
     *
     * If the target file is older than the origin file, it's always overwritten.
     * If the target file is newer, it is overwritten only when the
     * $overwriteNewerFiles option is set to true.
     *
     * @param string $originFile          The original filename
     * @param string $targetFile          The target filename
     * @param bool   $overwriteNewerFiles If true, target files newer than origin files are overwritten
     *
     * @throws FileNotFoundException When originFile doesn't exist
     * @throws IOException           When copy fails
     */
    public function copy($originFile, $targetFile, $overwriteNewerFiles = false): void
    {
        parent::copy($originFile, $targetFile, $overwriteNewerFiles);
    }

    /**
     * Creates a directory recursively.
     *
     * @param string|iterable $dirs The directory path
     * @param int             $mode The directory mode
     *
     * @throws IOException On any directory creation failure
     */
    public function mkdir($dirs, $mode = 0777): void
    {
        parent::mkdir($dirs, $mode);
    }

    /**
     * Checks the existence of files or directories.
     *
     * @param string|iterable $files A filename, an array of files, or a \Traversable instance to check
     *
     * @return bool true if the file exists, false otherwise
     */
    public function exists(string|iterable $files): bool
    {
        return parent::exists($files);
    }

    /**
     * Sets access and modification time of file.
     *
     * @param string $file A filename to create
     * @param int|null        $time  The touch time as a Unix timestamp, if not supplied the current system time is used
     * @param int|null        $atime The access time as a Unix timestamp, if not supplied the current system time is used
     *
     * @throws IOException When touch fails
     */
    public function touch($file, $time = null, $atime = null): void
    {
        parent::touch($this->getFilePath($file), $time, $atime);
    }

    /**
     * Removes files or directories.
     *
     * @param string $file
     */
    public function remove($file): void
    {
        parent::remove($this->getFilePath($file));
    }

    /**
     * Change mode for an array of files or directories.
     *
     * @param string|iterable $files     A filename, an array of files, or a \Traversable instance to change mode
     * @param int             $mode      The new mode (octal)
     * @param int             $umask     The mode mask (octal)
     * @param bool            $recursive Whether change the mod recursively or not
     *
     * @throws IOException When the change fails
     */
    public function chmod($files, $mode, $umask = 0000, $recursive = false): void
    {
        parent::chmod($files, $mode, $umask, $recursive);
    }

    /**
     * Change the owner of an array of files or directories.
     *
     * @param string|iterable $files     A filename, an array of files, or a \Traversable instance to change owner
     * @param string|int      $user      A user name or number
     * @param bool            $recursive Whether change the owner recursively or not
     *
     * @throws IOException When the change fails
     */
    public function chown($files, $user, $recursive = false): void
    {
        parent::chown($files, $user, $recursive);
    }

    /**
     * Change the group of an array of files or directories.
     *
     * @param string|iterable $files     A filename, an array of files, or a \Traversable instance to change group
     * @param string|int      $group     A group name or number
     * @param bool            $recursive Whether change the group recursively or not
     *
     * @throws IOException When the change fails
     */
    public function chgrp($files, $group, $recursive = false): void
    {
        parent::chgrp($files, $group, $recursive);
    }

    /**
     * Renames a file or a directory.
     *
     * @param string $origin    The origin filename or directory
     * @param string $target    The new filename or directory
     * @param bool   $overwrite Whether to overwrite the target if it already exists
     *
     * @throws IOException When target file or directory already exists
     * @throws IOException When origin cannot be renamed
     */
    public function rename($origin, $target, $overwrite = false): void
    {
        parent::rename($origin, $target, $overwrite);
    }

    /**
     * Creates a symbolic link or copy a directory.
     *
     * @param string $originDir     The origin directory path
     * @param string $targetDir     The symbolic link name
     * @param bool   $copyOnWindows Whether to copy files if on Windows
     *
     * @throws IOException When symlink fails
     */
    public function symlink($originDir, $targetDir, $copyOnWindows = false): void
    {
        parent::symlink($originDir, $targetDir, $copyOnWindows);
    }

    /**
     * Creates a hard link, or several hard links to a file.
     *
     * @param string          $originFile  The original file
     * @param string|string[] $targetFiles The target file(s)
     *
     * @throws FileNotFoundException When original file is missing or not a file
     * @throws IOException           When link fails, including if link already exists
     */
    public function hardlink($originFile, $targetFiles): void
    {
        parent::hardlink($originFile, $targetFiles);
    }

    /**
     * Resolves links in paths.
     *
     * With $canonicalize = false (default)
     *      - if $path does not exist or is not a link, returns null
     *      - if $path is a link, returns the next direct target of the link without considering the existence of the target
     *
     * With $canonicalize = true
     *      - if $path does not exist, returns null
     *      - if $path exists, returns its absolute fully resolved final version
     *
     * @param string $path         A filesystem path
     * @param bool   $canonicalize Whether or not to return a canonicalized path
     *
     * @return string|null
     */
    public function readlink(string $path, bool $canonicalize = false): ?string
    {
        return parent::readlink($path, $canonicalize);
    }

    /**
     * Given an existing path, convert it to a path relative to a given starting path.
     *
     * @param string $endPath   Absolute path of target
     * @param string $startPath Absolute path where traversal begins
     *
     * @return string Path of target relative to starting path
     */
    public function makePathRelative(string $endPath, string $startPath): string
    {
        return parent::makePathRelative($endPath, $startPath);
    }

    /**
     * Mirrors a directory to another.
     *
     * Copies files and directories from the origin directory into the target directory. By default:
     *
     *  - existing files in the target directory will be overwritten, except if they are newer (see the `override` option)
     *  - files in the target directory that do not exist in the source directory will not be deleted (see the `delete` option)
     *
     * @param string            $originDir The origin directory
     * @param string            $targetDir The target directory
     * @param \Traversable|null $iterator  Iterator that filters which files and directories to copy, if null a recursive iterator is created
     * @param array             $options   An array of boolean options
     *                                     Valid options are:
     *                                     - $options['override'] If true, target files newer than origin files are overwritten (see copy(), defaults to false)
     *                                     - $options['copy_on_windows'] Whether to copy files instead of links on Windows (see symlink(), defaults to false)
     *                                     - $options['delete'] Whether to delete files that are not in the source directory (defaults to false)
     *
     * @throws IOException When file type is unknown
     */
    public function mirror($originDir, $targetDir, \Traversable $iterator = null, $options = []): void
    {
        parent::mirror($originDir, $targetDir, $iterator, $options);
    }

    /**
     * Returns whether the file path is an absolute path.
     *
     * @param string $file A file path
     *
     * @return bool
     */
    public function isAbsolutePath(string $file): bool
    {
        return parent::isAbsolutePath($file);
    }

    /**
     * Creates a temporary file with support for custom stream wrappers.
     *
     * @param string $dir    The directory where the temporary filename will be created
     * @param string $prefix The prefix of the generated temporary filename
     *                       Note: Windows uses only the first three characters of prefix
     *
     * @return string The new temporary filename (with path), or throw an exception on failure
     */
    public function tempnam(string $dir, string $prefix, string $suffix = ''): string
    {
        return parent::tempnam($dir, $prefix);
    }

    /**
     * Atomically dumps content into a file.
     *
     * @param string          $filename The file to be written to
     * @param string|resource $content  The data to write into the file
     *
     * @throws IOException if the file cannot be written to
     */
    public function dumpFile($filename, $content): void
    {
        parent::dumpFile($filename, $content);
    }

    /**
     * Appends content to an existing file.
     *
     * @param string|resource $content The content to append
     * @param bool            $lock    Whether the file should be locked when writing to it
     *
     * @return void
     *
     * @throws IOException If the file is not writable
     */
    public function appendToFile(string $filename, $content, bool $lock = false): void
    {
        parent::appendToFile($filename, $content, $lock);
    }

    /**
     * Writes content to a file
     *
     * @param string $filename The file to write too
     * @param Resource $content Content to write to the file
     * @param int $startsAt start writing content at this location
     * @param null $length only write content until this length is reached. $content may be truncated if longer
     * than $length
     * @return false|int the position of the file pointer referenced by
     * handle as an integer; i.e., its offset into the file stream.
     */
    public function writeContentToFile($filename, $content, $startsAt = 0, $length = null)
    {
        $filename = $this->filesystemBasePath . $filename;
        $outputStream = fopen($filename, "c+b");
        try {
            fseek($outputStream, $startsAt);
            fwrite($outputStream, $content, $length);
            $pointer = ftell($outputStream);
        } finally {
            fclose($outputStream);
        }
        return $pointer;
    }

    /**
     * Calculates a hash of a file and compares it with the given hash
     *
     * @param $fileName
     * @param string $expectedHash expected hash of the file
     * @param string $algo the algorithm of the expected hash
     * @return bool true if the file hash is the same as the expected hash
     */
    public function hashIsValid($fileName, $expectedHash, $algo = "sha512"): bool
    {
        $filename = $this->getFilePath($fileName);
        return hash_file($algo, $filename) == $expectedHash;
    }

    /**
     * @param $fileName the id of the file
     * @return int|false the size of the file in bytes, or false (and generates an error
     * of level E_WARNING) in case of an error.
     *
     * @throws FileNotFoundException if the file is not found
     */
    public function size($fileName)
    {
        $filename = $this->getFilePath($fileName);
        if (file_exists($filename)) {
            return filesize($filename);
        } else {
            throw new FileNotFoundException("File is not found", 0, null, $filename);
        }
    }

    public function getFilePath($fileName): string
    {
        return $this->filesystemBasePath . $fileName;
    }
}
