<?php

namespace VCComponent\Laravel\Tag\Http\Controllers\Api\Frontend;

use Illuminate\Http\Request;
use VCComponent\Laravel\Tag\Repositories\TagRepository;
use VCComponent\Laravel\Tag\Transformers\TagTransformer;
use VCComponent\Laravel\Tag\Validators\TagValidator;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;

class TagController extends ApiController
{
    protected $repository;
    protected $validator;
    public function __construct(TagRepository $repository, TagValidator $validator, Request $request)
    {
        $this->repository = $repository;
        $this->entity = $repository->getEntity();
        $this->validator = $validator;
        $this->transformer = TagTransformer::class;
    }

    public function index(Request $request)
    {
        $query = $this->entity;

        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['name'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);
        $per_page = $request->has('per_page') ? (int) $request->get('per_page') : 15;
        $tags = $query->paginate($per_page);

        return $this->response->paginator($tags, new $this->transformer());
    }

    function list(Request $request)
    {
        $query = $this->entity;

        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['name'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);

        $tags = $query->get();

        return $this->response->item($tags, new $this->transformer());
    }

    public function show(Request $request, $id)
    {
        $tag = $this->repository->find($id);
        if (!$tag) {
            throw new NotFoundException('Test');
        }
        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer;
        }

        return $this->response->item($tag, $transformer);
    }
}
