<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use App\Models\File;

abstract class Controller {

    public function file_info ( $file ) {

        $file_name = $file->getClientOriginalName();
        $file_type = $file->getMimeType();
        $ext = $file->extension();
        $size = $file->getSize();

        if ($size >= 1024 && $size < 1048576) $size = round($size / 1024, 1) . ' KB';
        else if ($size >= 1048576 && $size < 1073741824) $size = round($size / 1048576, 1) . ' MB';
        else if ($size >= 1073741824) $size = round($size / 1073741824, 1) . ' GB';
        else $size = $size ?? 0 . ' Byte';

        $name = array_values(array_filter(explode('.', $file_name), function($item){ return $item; }));
        if ( count($name) > 1 ) $name = implode('.', array_slice($name, 0, -1));
        else if ( count($name) == 1 ) $name = $name[0];
        else $name = $file_name;

        $type = explode('/', $file_type)[0] ?? 'file';
        if ( $type != 'image' && $type != 'video' ) $type = 'file';

        return ['name' => $name, 'size' => $size, 'type' => $type, 'ext' => $ext];

    }
    public function upload_file ( $file, $dir ) {

        if ( !$file ) return null;
        $info = $this->file_info($file);
        $info['url'] = $file->store($dir . '/' . date('Y') . '/' . date('m') . '/' . date('d'));
        return $info;

    }
    public function delete_file ( $file ) {

        if ( !$file ) return false;
        if ( !Storage::exists($file) ) return false;
        Storage::delete($file);
        return true;

    }
    public function upload_files ( $files, $dir, $id ) {

        foreach ( $files as $file ) {

            if ( !$file ) continue;
            $data = $this->upload_file($file, $dir);
            $data['table'] = $dir;
            $data['column'] = $id;
            File::create($data);

        }

        return true;

    }
    public function delete_files ( $ids, $dir ) {

        foreach ( $ids as $id ) {

            $file = File::where('id', $id)->where('table', $dir)->first();
            $this->delete_file($file?->url);
            $file?->forceDelete();

        }

        return true;

    }
    public function slug ( $name ) {

        return strtolower(trim(preg_replace('/\./', '', preg_replace('/\s/', '-', $name))));

    }
    public function bool ( $value ) {

        $value = trim(strtolower($value));
        $values = ['true', '1', 't', 'yes', 'y', 'ya', 'yep', 'ok', 'on', 'done', 'always'];
        if ( in_array($value, $values) ) return true;
        return false;

    }
    public function integer ( $value ) {

        return (int)$value;

    }
    public function float ( $value, $decimal=2 ) {

        return round((float)$value, $decimal);

    }
    public function parse ( $value ) {

        $value = json_decode($value) ?? [];
        if ( getType($value) != 'array' ) $value = [$value];
        return $value;

    }
    public function random_key () {

        $key = '';
        for ( $i = 1; $i < 10; $i++ ) {
            $key .= rand(0, 9);
            if ( $i % 3 === 0 && $i != 9 ) $key .= '-';
        }
        return $key;

    }
    public function failed ( $response=[] ) {

        return response()->json(['status' => false, 'errors' => $response]);

    }
    public function success ( $response=[] ) {

        return response()->json(['status' => true] + $response);

    }
    public function user () {

        return auth()->guard('sanctum')->user();

    }
    public function date () {

        return date('Y-m-d H:i:s');

    }

}
