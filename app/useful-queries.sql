
-- Courses With Subjects List Query --

SELECT  c.`course_id`,c.`course_name`,c.`start_date`,c.`end_date`, cs.school_id, ( SELECT group_concat(s.name)    FROM  subjects s WHERE s.subject_id IN ( SELECT subject_id FROM course_subject csb WHERE csb.course_id = c.`course_id` )  )   AS Subjects  FROM `courses` c LEFT JOIN course_school cs ON c.course_id = cs.course_id  LEFT JOIN  course_teacher ct ON ct.course_id = c.course_id WHERE 1

-- Students Enrolled for Courses--


SELECT s.school_id, cs.course_id, cs.student_id  FROM `course_student` cs INNER JOIN students st ON st.`student_id`=cs.`student_id`   INNER JOIN schools s   ON st.`school_id`=s.`school_id`  WHERE 1


SELECT  c.`course_id`,c.`course_name`,c.`start_date`,c.`end_date`, cs.school_id, t.teacher_id, ( SELECT group_concat(s.name)    FROM  subjects s WHERE s.subject_id IN ( SELECT subject_id FROM course_subject csb WHERE csb.course_id = c.`course_id` )  )   AS Subjects  FROM `courses` c LEFT JOIN course_school cs ON c.course_id = cs.course_id  LEFT JOIN  teachers t ON t.school_id = cs.school_id WHERE 1 order by s.school_id