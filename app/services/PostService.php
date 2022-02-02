<?php

namespace App\services;

use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use App\Repositories\PostRepository;
use Illuminate\Support\Facades\Storage;

class PostService
{
    /**
     * @var PostRepository
     */
    protected $repository;
    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }
    public function store(array $input, UploadedFile $photo)
    {
        DB::beginTransaction();
        try {
            $path = $photo->store('public/images');
            $url = Storage::url($path);
            $post = $this->repository->create([
                'image'=>$url,
                'description'=> $input['description'],
                'user_id'=> $input['user_id']
            ]);
            //outro jeito de fazer 
            // $input['image'] = $url;
            // $post = Post::create([$input]);
        } catch (\Throwable $th) {
            DB::rollback();
            logger()->error($th);
            return [
            'success'=>false,
            'message'=>'Erro ao gravar post'];
        }
        DB::commit();
        return ['success'=>true,'message'=> 'Post Criado com sucesso!','data'=> $post];
    }
}
