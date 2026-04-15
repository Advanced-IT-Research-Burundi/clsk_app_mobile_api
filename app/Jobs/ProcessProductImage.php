<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\File;

class ProcessProductImage implements ShouldQueue
{
    use Queueable;

    protected $product;
    protected $tempPath;
    protected $originalName;

    /**
     * Create a new job instance.
     */
    public function __construct(Product $product, string $tempPath, string $originalName)
    {
        $this->product = $product;
        $this->tempPath = $tempPath;
        $this->originalName = $originalName;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Check if temp file exists
        if (!File::exists($this->tempPath)) {
            return;
        }

        // Generate final path
        $extension = File::extension($this->tempPath) ?: File::extension($this->originalName);
        $imageName = time() . '_' . uniqid() . '.' . $extension;
        $destinationPath = public_path('uploads/products');
        
        // Ensure directory exists
        if (!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, 0755, true);
        }

        // Move file
        $finalPath = 'uploads/products/' . $imageName;
        File::move($this->tempPath, public_path($finalPath));

        // Create database record
        $this->product->photos()->create([
            'url' => $finalPath
        ]);
    }
}
