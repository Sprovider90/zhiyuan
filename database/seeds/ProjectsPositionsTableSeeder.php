<?php
use Illuminate\Database\Seeder;
use App\Models\ProjectsPositions;
class ProjectsPositionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projectsPositions = factory(ProjectsPositions::class)
            ->times(10)
            ->make();


        // 将数据集合转换为数组，并插入到数据库中
        ProjectsPositions::insert($projectsPositions->toArray());
    }
}
