<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->boolean('is_published')->default(false)->after('updated_at');
            $table->timestamp('published_at')->nullable()->after('is_published');
            $table->string('title')->nullable()->after('published_at');
            $table->string('slug')->nullable()->after('title');
            $table->text('excerpt')->nullable()->after('slug');
            $table->longText('content')->nullable()->after('excerpt');
            $table->string('featured_image')->nullable()->after('content');
            $table->unsignedBigInteger('author_id')->nullable()->after('featured_image');
            $table->integer('views')->default(0)->after('author_id');

            $table->foreign('author_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn(['is_published', 'published_at', 'title', 'slug', 'excerpt', 'content', 'featured_image', 'author_id', 'views']);
        });
    }
};
