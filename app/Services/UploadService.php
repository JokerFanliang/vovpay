<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use App\Exceptions\CustomServiceException;

class UploadService
{
    protected $allowed_ext = ["jpg", 'jpeg', 'png'];

    public function upload( UploadedFile $file, string $folder, string $file_prefix)
    {

        // 构建存储的文件夹规则
        $folder_name = "uploads/$folder";

        // 文件具体存储的物理路径，`public_path()` 获取的是 `public` 文件夹的物理路径。
        $upload_path = public_path() . '/' . $folder_name;

        // 获取文件的后缀名，因图片从剪贴板里黏贴时后缀名为空，所以此处确保后缀一直存在
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        // 拼接文件名,加前缀是为了增加辨析度
        $filename = $file_prefix . '_' . time() . '.' . $extension;

        // 如果上传的不是图片将终止操作
        if ( !in_array($extension, $this->allowed_ext)) {
            throw new CustomServiceException('上传格式错误');
        }

        $file->move($upload_path, $filename);

        return "/$folder_name/$filename";
    }
}