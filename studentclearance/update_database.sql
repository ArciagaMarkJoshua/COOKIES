-- Add approved_by field if it doesn't exist
ALTER TABLE student_clearance_status 
ADD COLUMN IF NOT EXISTS approved_by varchar(100) DEFAULT NULL;

-- Update existing approved records with staff names
UPDATE student_clearance_status scs
JOIN staff s ON scs.StaffID = s.StaffID
SET scs.approved_by = CONCAT(s.FirstName, ' ', s.LastName)
WHERE scs.status = 'Approved' 
AND (scs.approved_by IS NULL OR scs.approved_by = '');

-- Update any remaining approved records that might have been missed
UPDATE student_clearance_status
SET approved_by = 'System Administrator'
WHERE status = 'Approved' 
AND (approved_by IS NULL OR approved_by = '');

-- Add index for better performance if it doesn't exist
SELECT IF(
    NOT EXISTS(
        SELECT 1 FROM information_schema.statistics 
        WHERE table_schema = DATABASE()
        AND table_name = 'student_clearance_status' 
        AND index_name = 'idx_approved_by'
    ),
    'ALTER TABLE student_clearance_status ADD INDEX idx_approved_by (approved_by)',
    'SELECT "Index idx_approved_by already exists"'
) INTO @sql;
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Show any records still missing approved_by
SELECT scs.*, s.FirstName, s.LastName
FROM student_clearance_status scs
LEFT JOIN staff s ON scs.StaffID = s.StaffID
WHERE scs.status = 'Approved' 
AND (scs.approved_by IS NULL OR scs.approved_by = ''); 