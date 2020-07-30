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
        for ($i=1; $i<4 ; $i++){
            $s = 0;
            $projectsStages = factory(ProjectsStages::class)
                ->times(100)
                ->make()
                ->each(function ($projectsStage, $index)
                use ($project_ids,$threshold_ids, $faker,$i,&$s)
                {
                    $start_date = '';
                    $end_date   = '';
                    $stage_name = '';
                    switch ($i){
                        case 1:
                            $stage_name.='阶段一';
                            $month = rand(36,24);
                            $start_date = date('Y-m-d',strtotime('-'.$month.' month'));
                            $end_date   = date('Y-m-d',strtotime('-'.$month+rand(1,11) .' month'));
                            break;
                        case 2:
                            $stage_name.='阶段二';
                            $month = rand(24,12);
                            $start_date = date('Y-m-d',strtotime('-'.$month.' month'));
                            $end_date   = date('Y-m-d',strtotime('-'.$month+rand(1,11) .' month'));
                            break;
                        case 3:
                            $stage_name.='阶段三';
                            $month = rand(12,0);
                            $start_date = date('Y-m-d',strtotime('-'.$month.' month'));
                            $end_date   = date('Y-m-d',strtotime('-'.$month+rand(1,11) .' month'));
                            break;
                    }
                    $projectsStage->project_id = $project_ids[$s];
                    $projectsStage->threshold_id    = $faker->randomElement($threshold_ids);
                    $projectsStage->stage_name      = $stage_name;
                    $projectsStage->start_date      = $start_date;
                    $projectsStage->end_date        = $end_date;
                    $projectsStage->stage           = $i;
                    $projectsStage->default         = 1;
                    $s+=1;
                });
            // 将数据集合转换为数组，并插入到数据库中
            ProjectsStages::insert($projectsStages->toArray());
        }

    }
}
