<?php

use App\Models\Projects;
use Faker\Generator;
use Illuminate\Database\Seeder;
use App\Models\ProjectsPositions;
use App\Models\ProjectsAreas;

class ProjectsPositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $project_ids = Projects::all()->pluck('id')->toArray();
        $project_area_ids = ProjectsAreas::all()->pluck('id')->toArray();
        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);
        $projectsPositions = factory(ProjectsPositions::class)
            ->times(200)
            ->make()
            ->each(function ($projectPos, $index)
            use ($project_ids,$project_area_ids, $faker)
            {
                $projectPos->project_id = $faker->randomElement($project_ids);
                $projectPos->area_id = $faker->randomElement($project_area_ids);
            });


        // 将数据集合转换为数组，并插入到数据库中
        ProjectsPositions::insert($projectsPositions->toArray());
    }
}
