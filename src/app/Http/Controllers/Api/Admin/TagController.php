<?php

namespace VCComponent\Laravel\Tag\Http\Controllers\Api\Admin;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use VCComponent\Laravel\Tag\Events\TagCreatedByAdminEvent;
use VCComponent\Laravel\Tag\Events\TagDeletedByAdminEvent;
use VCComponent\Laravel\Tag\Events\TagUpdatedByAdminEvent;
use VCComponent\Laravel\Tag\Repositories\TagRepository;
use VCComponent\Laravel\Tag\Transformers\TagTransformer;
use VCComponent\Laravel\Tag\Validators\TagValidator;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;

class TagController extends ApiController
{
    protected $repository;
    protected $validator;
    public function __construct(TagRepository $repository, TagValidator $validator)
    {
        $this->repository = $repository;
        $this->entity = $repository->getEntity();
        $this->validator = $validator;
        if (config('tag.auth_middleware.admin.middleware') !== '') {
            $this->middleware(
                config('tag.auth_middleware.admin.middleware'),
                ['except' => config('tag.auth_middleware.admin.except')]
            );
        } else {
            throw new Exception("Admin middleware configuration is required");
        }
        if (isset(config('tag.transformers')['tag'])) {
            $this->transformer = config('tag.transformers.tag');
        } else {
            $this->transformer = TagTransformer::class;
        }

    }
    public function index(Request $request)
    {
        $query = $this->entity;
        $query = $this->getStatus($request, $query);
        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['name'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);
        $per_page = $request->has('per_page') ? (int) $request->get('per_page') : 15;
        $tags = $query->paginate($per_page);

        return $this->response->paginator($tags, new $this->transformer());
    }
    public function store(Request $request)
    {
        $this->validator->isValid($request, 'RULE_CREATE');
        $data = $request->all();
        $tag = $this->repository->create($data);

        event(new TagCreatedByAdminEvent($tag));

        return $this->response->item($tag, new $this->transformer());
    }
    public function show(Request $request, $id)
    {
        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer;
        }

        $tag = $this->repository->findById($request, $id);

        return $this->response->item($tag, $transformer);
    }
    function list(Request $request) {
        $query = $this->entity;
        $query = $this->getStatus($request, $query);
        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['name'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);

        $tags = $query->get();

        return $this->response->collection($tags, new $this->transformer());
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => [
                'required',
                Rule::unique('tags')->ignore($id),
            ],
        ]);
        $data = $request->all();
        $tag = $this->repository->update($data, $id);

        event(new TagUpdatedByAdminEvent($tag));

        return $this->response->item($tag, new $this->transformer());
    }

    public function destroy($id)
    {
        $tag = $this->entity->find($id);
        if (!$tag) {
            throw new NotFoundException('tag');
        }

        $this->repository->delete($id);

        event(new TagDeletedByAdminEvent($tag));

        return $this->success();
    }
    public function bulkUpdateStatus(Request $request)
    {
        $this->repository->bulkUpdateStatus($request);
        return $this->success();
    }

    public function updateStatus(Request $request, $id)
    {
        $this->repository->updateStatus($request, $id);
        return $this->success();
    }
    public function getStatus($request, $query)
    {

        if ($request->has('status')) {
            $pattern = '/^\d$/';

            if (!preg_match($pattern, $request->status)) {
                throw new Exception('The input status is incorrect');
            }

            $query = $query->where('status', $request->status);
        }

        return $query;
    }
}
