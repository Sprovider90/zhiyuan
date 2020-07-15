<?php
use Illuminate\Database\Seeder;
use App\Models\Projects;
use App\Models\ProjectsAreas;
class ProjectsAreasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $project_ids = Projects::all()->pluck('id')->toArray();

        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);

        $projects = factory(ProjectsAreas::class)
            ->times(100)
            ->make()
            ->each(function ($projectsArea, $index)
            use ($project_ids, $faker)
            {

                $projectsArea->project_id = $faker->unique()->randomElement($project_ids);
            });

        // 将数据集合转换为数组，并插入到数据库中
        ProjectsAreas::insert($projects->toArray());
    }
}
