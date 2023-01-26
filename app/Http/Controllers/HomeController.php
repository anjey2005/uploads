<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Upload;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * выбор столбца сортировки
     *
     * @param string $value
     * @return string
     */
    private function SortBy($value)
    {
        $columns = [
            'date' => 'created_at',
            'like' => 'likes',
            'download' => 'uploads',
        ];
        return $columns[isset($columns[$value]) ? $value : array_key_first($columns)];
    }

    /**
     * вывод объектов
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        $category_id = $request->get('category', 0);

        $uploads = Upload::with('category')
            ->when($categories->has($category_id) ? $category_id : false, function ($query, $category) {
                return $query->where('category_id', $category);
            })
            ->where('status', 'accepted')
            ->where('public', true)
            ->orderByDesc($this->SortBy($request->get('sortBy')))
            ->orderByDesc('id')
            ->paginate(9)
            ->withQueryString();

        return view('pages.home', compact('uploads', 'categories'));
    }

    /**
     * вывод объектов пользователя
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function my_uploads(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        $category_id = $request->get('category', 0);

        $uploads = Upload::with('category')
            ->when($categories->has($category_id) ? $category_id : 0, function ($query, $category) {
                return $query->where('category_id', $category);
            })
            ->where('status', 'accepted')
            ->where('user_id', $request->user()['id'])
            ->orderByDesc($this->SortBy($request->get('sortBy')))
            ->paginate(9)
            ->withQueryString();

        return view('pages.home', compact('uploads', 'categories'));
    }

}
