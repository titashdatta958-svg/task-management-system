<?php namespace App\Models;

use CodeIgniter\Model;

class AnalyticsModel extends Model
{
    protected $table = 'task_members';

    /**
     * PERFORMANCE FORMULA
     * (due_date - completed_at) → seconds
     * Average per member
     */
    public function getPerformance($year, $month)
    {
        $db = \Config\Database::connect();

        $sql = "
            SELECT 
                m.id AS member_id,
                m.name,
                AVG(
                    TIMESTAMPDIFF(
                        SECOND,
                        tm.completed_at,
                        t.due_date
                    )
                ) AS performance
            FROM task_members tm
            JOIN members m ON m.id = tm.member_id
            JOIN tasks t ON t.id = tm.task_id
            WHERE tm.status = 'completed'
              AND YEAR(tm.completed_at) = ?
        ";

        $params = [$year];

        if ($month !== 'all') {
            $sql .= " AND MONTH(tm.completed_at) = ? ";
            $params[] = $month;
        }

        $sql .= " GROUP BY m.id ORDER BY performance DESC";

        return $db->query($sql, $params)->getResultArray();
    }
}
