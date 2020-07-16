<?php
use Illuminate\Database\Seeder;
use App\Models\Projects;
use App\Models\ProjectsStages;
use App\Models\Thresholds;

class ProjectsStagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $project_ids = Projects::all()->pluck('id')->toArray();
        $threshold_ids = Thresholds::all()->pluck('id')->toArray();
        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);

        $projectsStages = factory(ProjectsStages::class)
            ->times(100)
            ->make()
            ->each(function ($projectsStage, $index)
            use ($project_ids,$threshold_ids, $faker)
            {
                $projectsStage->project_id = $faker->randomElement($project_ids);
                $projectsStage->threshold_id = $faker->randomElement($threshold_ids);

            });

        // 将数据集合转换为数组，并插入到数据库中
        ProjectsStages::insert($projectsStages->toArray());
    }
}
