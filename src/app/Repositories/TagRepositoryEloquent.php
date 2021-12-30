<?php

namespace VCComponent\Laravel\Tag\Repositories;

use Exception;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use VCComponent\Laravel\Tag\Entities\Tag;
use VCComponent\Laravel\Tag\Repositories\TagRepository;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;

/**
 * Class AccountantRepositoryEloquent.
 */
class TagRepositoryEloquent extends BaseRepository implements TagRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        if (isset(config('tag.models')['tag'])) {
            return config('tag.models.tag');
        } else {
            return Tag::class;
        }
    }

    public function getEntity()
    {
        return $this->model;
    }
    public function findById($request, $id)
    {
        if (!tag::find($id)) {
            throw new NotFoundException('Tags');
        }
        $tag = $this->find($id);
        return $tag;
    }

    public function updateStatus($request, $id)
    {
        $tag = $this->find($id);

        $tag->status = $request->input('status');
        $tag->save();
    }

    public function bulkUpdateStatus($request)
    {

        $data = $request->all();
        $tag  = $this->findWhereIn("id", $request->id);

        if (count($request->id) > $tag->count()) {
            throw new NotFoundException("tag");
        }

        $result = $this->model->whereIn("id", $request->id)->update(['status' => $data['status']]);

        return $result;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getListTags($number = null)
    {
        try {
            $query = $this->model->where('status', 1)
                ->orderBy('created_at', 'desc');
            if (!is_null($number)) {
                return $query->limit($number)->get();
            }
            return $query->get();

        } catch (Exception $e) {
            throw new NotFoundException($e);
        }
    }
    public function getListPaginatedTags($per_page)
    {
        try {
            $query = $this->model->where('status', 1)
            ->orderBy('created_at', 'desc');
            return $query->paginate($per_page);
        } catch (Exception $e) {
            throw new NotFoundException($e);
        }
    }

    public function getListTranslatableTags($number = null)
    {
        try {
            $query = $this->model->where('status', 1)
                ->with('languages')
                ->orderBy('created_at', 'desc');
            if (!is_null($number)) {
                return $query->limit($number)->get();
            }
            return $query->get();

        } catch (Exception $e) {
            throw new NotFoundException($e);
        }
    }
    public function getListPaginatedTranslatableTags($per_page)
    {
        try {
            $query = $this->model->where('status', 1)
                ->with('languages')
                ->orderBy('created_at', 'desc');
            return $query->paginate($per_page);

        } catch (Exception $e) {
            throw new NotFoundException($e);
        }
    }
}
