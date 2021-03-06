<?php namespace Cms\Traits;

use Lang;
use File;
use Config;
use Request;
use Input;
use Exception;
use SystemException;
use ApplicationException;
use Cms\Classes\Asset;
use Cms\Classes\Theme;
use Editor\Classes\ApiHelpers;
use October\Rain\Filesystem\Definitions as FileDefinitions;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Implements Assets CRUD operations for the CMS Editor Extension
 */
trait EditorExtensionAssetsCrud
{
    protected function command_onAssetCreateDirectory()
    {
        $metadata = $this->getRequestMetadata();
        $documentData = $this->getRequestDocumentData();
        $this->validateRequestTheme($metadata);

        $newName = trim(ApiHelpers::assertGetKey($documentData, 'name'));
        $parent = ApiHelpers::assertGetKey($documentData, 'parent');

        if (!strlen($newName)) {
            throw new ApplicationException(Lang::get('cms::lang.asset.name_cant_be_empty'));
        }

        if (!$this->validateAssetPath($newName)) {
            throw new ApplicationException(Lang::get('cms::lang.asset.invalid_path'));
        }

        if (strlen($parent) && !$this->validateAssetPath($parent)) {
            throw new ApplicationException(Lang::get('cms::lang.asset.invalid_path'));
        }

        if (!$this->validateAssetName($newName)) {
            throw new ApplicationException(Lang::get('cms::lang.asset.invalid_name'));
        }

        $newFullPath = $this->getAssetsPath($this->getTheme()).'/'.$parent.'/'.$newName;
        if (file_exists($newFullPath) && is_dir($newFullPath)) {
            throw new ApplicationException(Lang::get('cms::lang.asset.already_exists'));
        }

        if (!File::makeDirectory($newFullPath)) {
            throw new ApplicationException(Lang::get(
                'cms::lang.cms_object.error_creating_directory',
                ['name' => $newName]
            ));
        }
    }

    protected function command_onAssetDelete()
    {
        $metadata = $this->getRequestMetadata();
        $documentData = $this->getRequestDocumentData();
        $this->validateRequestTheme($metadata);

        $fileList = ApiHelpers::assertGetKey($documentData, 'files');
        ApiHelpers::assertIsArray($fileList);

        // Delete leaves first
        //
        usort($fileList, function($a, $b) {
            return strlen($b) - strlen($a);
        });

        $assetsPath = $this->getAssetsPath($this->getTheme());

        foreach ($fileList as $path) {
            if (!$this->validateAssetPath($path)) {
                throw new ApplicationException(Lang::get('cms::lang.asset.invalid_path'));
            }

            $fullPath = $assetsPath.'/'.$path;
            if (File::exists($fullPath)) {
                if (!File::isDirectory($fullPath)) {
                    if (!@File::delete($fullPath)) {
                        throw new ApplicationException(Lang::get(
                            'cms::lang.asset.error_deleting_file',
                            ['name' => $path]
                        ));
                    }
                }
                else {
                    $empty = File::isDirectoryEmpty($fullPath);
                    if (!$empty) {
                        throw new ApplicationException(Lang::get(
                            'cms::lang.asset.error_deleting_dir_not_empty',
                            ['name' => $path]
                        ));
                    }

                    if (!@rmdir($fullPath)) {
                        throw new ApplicationException(Lang::get(
                            'cms::lang.asset.error_deleting_dir',
                            ['name' => $path]
                        ));
                    }
                }
            }
        }
    }

    protected function command_onAssetRename()
    {
        $metadata = $this->getRequestMetadata();
        $documentData = $this->getRequestDocumentData();
        $this->validateRequestTheme($metadata);

        $newName = trim(ApiHelpers::assertGetKey($documentData, 'name'));
        if (!strlen($newName)) {
            throw new ApplicationException(Lang::get('cms::lang.asset.name_cant_be_empty'));
        }

        if (!$this->validateAssetPath($newName)) {
            throw new ApplicationException(Lang::get('cms::lang.asset.invalid_path'));
        }

        if (!$this->validateAssetName($newName)) {
            throw new ApplicationException(Lang::get('cms::lang.asset.invalid_name'));
        }

        $originalPath = trim(ApiHelpers::assertGetKey($documentData, 'originalPath'));
        if (!$this->validateAssetPath($originalPath)) {
            throw new ApplicationException(Lang::get('cms::lang.asset.invalid_path'));
        }

        $originalFullPath = $this->getAssetFullPath($originalPath);
        if (!file_exists($originalFullPath)) {
            throw new ApplicationException(Lang::get('cms::lang.asset.original_not_found'));
        }

        $assetExtensions = FileDefinitions::get('assetExtensions');
        if (!is_dir($originalFullPath) && !$this->validateAssetFileType($newName, $assetExtensions)) {
            throw new ApplicationException(Lang::get(
                'cms::lang.asset.type_not_allowed',
                ['allowed_types' => implode(', ', $assetExtensions)]
            ));
        }

        $newFullPath = $this->getAssetFullPath(dirname($originalPath).'/'.$newName);
        if (file_exists($newFullPath) && $newFullPath !== $originalFullPath) {
            throw new ApplicationException(Lang::get('cms::lang.asset.already_exists'));
        }

        if (!@rename($originalFullPath, $newFullPath)) {
            throw new ApplicationException(Lang::get('cms::lang.asset.error_renaming'));
        }
    }

    protected function command_onAssetMove()
    {
        $metadata = $this->getRequestMetadata();
        $documentData = $this->getRequestDocumentData();
        $this->validateRequestTheme($metadata);

        $selectedList = ApiHelpers::assertGetKey($documentData, 'source');
        ApiHelpers::assertIsArray($selectedList);

        if (!count($selectedList)) {
            throw new ApplicationException(Lang::get('cms::lang.asset.selected_files_not_found'));
        }

        $destinationDir = ApiHelpers::assertGetKey($documentData, 'destination');
        if (!strlen($destinationDir)) {
            throw new ApplicationException(Lang::get('cms::lang.asset.select_destination_dir'));
        }

        if (!$this->validateAssetPath($destinationDir)) {
            throw new ApplicationException(Lang::get('cms::lang.asset.invalid_path'));
        }

        $destinationFullPath = $this->getAssetFullPath($destinationDir);
        if (!file_exists($destinationFullPath) || !is_dir($destinationFullPath)) {
            throw new ApplicationException(Lang::get('cms::lang.asset.destination_not_found'));
        }

        foreach ($selectedList as $path) {
            if (!$this->validateAssetPath($path)) {
                throw new ApplicationException(Lang::get('cms::lang.asset.invalid_path'));
            }

            $basename = basename($path);
            $originalFullPath = $this->getAssetFullPath($path);
            $newFullPath = rtrim($destinationFullPath, '/').'/'.$basename;
            $safeDir = $this->getAssetsPath($this->getTheme());

            if ($originalFullPath == $newFullPath) {
                continue;
            }

            if (is_file($originalFullPath)) {
                if (!@File::move($originalFullPath, $newFullPath)) {
                    throw new ApplicationException(Lang::get(
                        'cms::lang.asset.error_moving_file',
                        ['file' => $basename]
                    ));
                }
            }
            elseif (is_dir($originalFullPath)) {
                if (!@File::copyDirectory($originalFullPath, $newFullPath)) {
                    throw new ApplicationException(Lang::get(
                        'cms::lang.asset.error_moving_directory',
                        ['dir' => $basename]
                    ));
                }

                if (strpos($originalFullPath, '../') !== false) {
                    throw new ApplicationException(Lang::get(
                        'cms::lang.asset.error_deleting_directory',
                        ['dir' => $basename]
                    ));
                }

                if (strpos($originalFullPath, $safeDir) !== 0) {
                    throw new ApplicationException(Lang::get(
                        'cms::lang.asset.error_deleting_directory',
                        ['dir' => $basename]
                    ));
                }

                if (!@File::deleteDirectory($originalFullPath)) {
                    throw new ApplicationException(Lang::get(
                        'cms::lang.asset.error_deleting_directory',
                        ['dir' => $basename]
                    ));
                }
            }
        }
    }

    protected function command_onAssetUpload()
    {
        $metadata = [
            'theme' => Request::input('theme')
        ];

        $this->validateRequestTheme($metadata);

        $uploadedFile = Input::file('file');
        if (!is_object($uploadedFile)) {
            return;
        }

        $fileName = $uploadedFile->getClientOriginalName();

        /*
         * Check valid upload
         */
        if (!$uploadedFile->isValid()) {
            throw new ApplicationException(Lang::get('cms::lang.asset.file_not_valid'));
        }

        /*
         * Check file size
         */
        $maxSize = UploadedFile::getMaxFilesize();
        if ($uploadedFile->getSize() > $maxSize) {
            throw new ApplicationException(Lang::get(
                'cms::lang.asset.too_large',
                ['max_size' => File::sizeToString($maxSize)]
            ));
        }

        /*
         * Check for valid file extensions
         */
        $assetExtensions = FileDefinitions::get('assetExtensions');
        if (!$this->validateAssetFileType($fileName, $assetExtensions)) {
            throw new ApplicationException(Lang::get(
                'cms::lang.asset.type_not_allowed',
                ['allowed_types' => implode(', ', $assetExtensions)]
            ));
        }

        /*
         * Validate destination path 
         */
        $destinationDir = trim(Request::input('destination'));
        if (!strlen($destinationDir)) {
            throw new ApplicationException(Lang::get('cms::lang.asset.select_destination_dir'));
        }

        if (!$this->validateAssetPath($destinationDir)) {
            throw new ApplicationException(Lang::get('cms::lang.asset.invalid_path'));
        }

        $destinationFullPath = $this->getAssetFullPath($destinationDir);
        if (!file_exists($destinationFullPath) || !is_dir($destinationFullPath)) {
            throw new ApplicationException(Lang::get('cms::lang.asset.destination_not_found'));
        }

        /*
         * Accept the uploaded file
         */
        $uploadedFile->move($destinationFullPath, $uploadedFile->getClientOriginalName());
    }

    private function getAssetFullPath($path)
    {
        return $this->getAssetsPath($this->getTheme()).'/'.ltrim($path, '/');
    }

    private function validateAssetPath($path)
    {
        if (!preg_match('/^[\@0-9a-z\.\s_\-\/]+$/i', $path)) {
            return false;
        }

        if (strpos($path, '..') !== false || strpos($path, './') !== false) {
            return false;
        }

        return true;
    }

    private function validateAssetName($name)
    {
        if (!preg_match('/^[\@0-9a-z\.\s_\-]+$/i', $name)) {
            return false;
        }

        if (strpos($name, '..') !== false) {
            return false;
        }

        return true;
    }

    private function validateAssetFileType($name, $assetExtensions)
    {
        $extension = strtolower(File::extension($name));
        if (!in_array($extension, $assetExtensions)) {
            return false;
        }

        return true;
    }
}