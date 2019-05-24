<?php

namespace Voicecode\NavigationManager\Http\Controllers\Traits;

use Voicecode\NavigationManager\Models\NavigationItem;

trait NavigationItemsTrait
{
    /**
     * Add a new navigation item.
     */
    public function addItem($navigationId)
    {
        // Validation
        $data = request()->validate([
            'navigation_id' => 'required|numeric|min:1',
            'name' => 'required|string',
            'url' => 'required|string',
            'target' => 'required|string',
        ]);

        // Set item at the last position.
        $data['sequence'] = 99;

        // Add navigation item.
        $item = NavigationItem::create($data);

        // Refresh cache
        $this->refreshCache($navigationId);

        return response()->json($item);
    }

    /**
     * Update a navigation item.
     */
    public function updateItem($navigationId, $itemId)
    {
        // Validation
        $data = request()->validate([
            'name' => 'required|string',
            'url' => 'required|string',
            'target' => 'required|string',
        ]);

        // Update the navigation item.
        $item = NavigationItem::find($itemId);
        $item->update($data);

        // Refresh cache
        $this->refreshCache($navigationId);

        return response()->json($item);
    }

    /**
     * Detele an item from a navigation.
     */
    public function deleteItem($navigationId, $itemId)
    {
        // Delete the item.
        $item = NavigationItem::where('id', $itemId)
            ->where('navigation_id', $navigationId)
            ->delete();

        // Refresh cache
        $this->refreshCache($navigationId);

        return response()->json([
            'success' => true,
            'message' => 'The item has been deleted.',
        ]);
    }

    /**
     * Update the children of a navigation.
     */
    public function updatePositions($navigationId)
    {
        // Validation
        $data = request()->validate([
            'items' => 'required|array',
        ]);

        // Loop through all navigation items
        foreach ($data['items'] as $item) {
            $parentId = (isset($item['parent_id'])) ? $item['parent_id'] : null;

            NavigationItem::where('id', $item['id'])->update([
                'order' => $item['order'],
                'parent_id' => $parentId,
            ]);
        }

        // Refresh cache
        $this->refreshCache($navigationId);

        return response()->json([
            'success' => true,
            'message' => 'The positions have been updated.',
        ]);
    }
}
