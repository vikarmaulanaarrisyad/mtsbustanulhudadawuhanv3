<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $menus = Menu::with('children')
        //     ->orderBy('menu_parent_id')
        //     ->orderBy('menu_position')
        //     ->get();

        $pages = Page::all();
        $modules = Page::all();

        $menus = Menu::orderBy('menu_parent_id')
            ->orderBy('menu_position')
            ->get();

        return view('admin.menus.index', compact('menus', 'pages', 'modules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'menu_title' => 'nullable|string|max:255',
            // 'menu_url' => 'nullable|string|max:255',
            'menu_target' => 'required|in:_blank,_self,_parent,_top',
            'menu_type' => 'required|in:pages,links,modules',
            'menu_position' => 'nullable|integer',
        ]);

        // Create menu with improved logic for menu_title
        $menuTitle = Page::where('slug', $request->menu_url)->first();

        $menu_title = $request->menu_title ?? ucfirst(str_replace('/', '', $request->menu_url));
        $menu = Menu::create([
            'menu_title' => $menuTitle->title,
            'menu_url' => $request->menu_url == ""
                ? ($request->menu_parent_id == 0 ? '#' : Str::slug($menu_title))
                : $request->menu_url,
            'menu_slug' => $request->menu_url == ""
                ? ($request->menu_parent_id == 0 ? '#' : Str::slug($menu_title))
                : $request->menu_url,
            'menu_target' => $request->menu_target,
            'menu_type' => $request->menu_type,
            'menu_parent_id' => $request->menu_parent_id ?? 0,
            'menu_position' => Menu::max('menu_position') + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil ditambahkan!',
            'menu' => $menu
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $menu = Menu::findOrfail($id);

        return response()->json(['data' => $menu]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'menu_title' => 'required|string|max:255',
            // 'menu_url' => 'nullable|string|max:255',
            'menu_target' => 'required|in:_blank,_self,_parent,_top',
            'menu_type' => 'required|in:pages,links,modules',
            'menu_position' => 'nullable|integer',
        ]);

        $menu = Menu::findOrFail($id);

        $menu->update([
            'menu_title' => $request->menu_title,
            'menu_url' => $request->menu_url == "" ? $request->menu_parent_id == 0 ? '#' : Str::slug($request->menu_title) : $request->menu_url,
            'menu_slug' => $request->menu_url == "" ? $request->menu_parent_id == 0 ? '#' : Str::slug($request->menu_title) : $request->menu_url,
            'menu_target' => $request->menu_target,
            'menu_type' => $request->menu_type,
            'menu_parent_id' => $request->menu_parent_id ?? 0,
            'menu_position' => $request->menu_position ?? $menu->menu_position,
        ]);

        return response()->json([
            'message' => 'Menu berhasil diperbarui!',
            'menu' => $menu
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return response()->json([
            'message' => 'Menu berhasil dihapus!'
        ], 200);
    }

    public function updateOrder(Request $request)
    {
        $menus = $request->menu;

        foreach ($menus as $item) {
            Menu::where('id', $item['id'])->update([
                'menu_parent_id' => $item['parent_id'],
                'menu_position' => $item['position'],
            ]);
        }

        return response()->json(['message' => 'Urutan menu berhasil diperbarui!']);
    }

    public function getSubmenu($menu_id)
    {
        $submenus = Menu::where('menu_parent_id', $menu_id)
            ->get();

        return response()->json($submenus);
    }

    public function getAllMenu()
    {
        $menus = Menu::where('menu_parent_id', 0)
            ->where('menu_type', 'pages')
            ->where('menu_url', '!=', '/')
            ->get();

        return response()->json([
            'data' => $menus
        ]);
    }

    public function getAllSubmenu(Request $request)
    {
        $submenus = Menu::where('menu_parent_id', $request->menu_id)->get();

        return response()->json([
            'success' => true,
            'data' => $submenus
        ]);
    }
}
