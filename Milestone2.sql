-- Create Users table
CREATE TABLE IF NOT EXISTS Users (
    user_id VARCHAR(255) NOT NULL PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    DOB DATE NOT NULL,
    squat_max INT,
    bench_max INT,
    dl_max INT
);

-- Create Exercises table
CREATE TABLE IF NOT EXISTS Exercises (
    exercise_name VARCHAR(255) PRIMARY KEY,
    muscle VARCHAR(255),
    description TEXT
);

-- Create Exercise_History table
CREATE TABLE IF NOT EXISTS Exercise_History (
    user_id VARCHAR(255),
    exercise VARCHAR(255),
    date DATE,
    set_number INT AUTO_INCREMENT,
    weight INT,
    reps INT,
    CONSTRAINT CHK_Valid CHECK (weight > 0 AND reps > 0),
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (exercise) REFERENCES Exercises(exercise_name),
    PRIMARY KEY (user_id, exercise, date, set_number) -- Composite primary key

);

-- Create Body_Weight_History table
CREATE TABLE IF NOT EXISTS Body_Weight_History (
    user_id VARCHAR(255),
    date DATE,
    weight DECIMAL(5,2),
    CHECK (weight > 0),
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    PRIMARY KEY (user_id, date) -- Composite primary key
);

-- Create Goals table
CREATE TABLE IF NOT EXISTS Goals (
    goal_type VARCHAR(255),
    exercise VARCHAR(255),
    PRIMARY KEY (goal_type, exercise)
);

-- Create User_goals table
CREATE TABLE IF NOT EXISTS User_goals (
    user_id VARCHAR(255),
    goal_type VARCHAR(255),
    exercise VARCHAR(255),
    target_date DATE,
    goal_value INT,
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (goal_type, exercise) REFERENCES Goals(goal_type, exercise),
    PRIMARY KEY (user_id, goal_type, exercise)
);

-- Create Meal_History table
CREATE TABLE IF NOT EXISTS Meal_History (
    user_id VARCHAR(255),
    date DATE,
    meal_number INT AUTO_INCREMENT,
    calories INT,
    CHECK (calories>0),
    carbs INT,
    protein INT,
    fat INT,
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    PRIMARY KEY (user_id, date, meal_number) -- Composite primary key
);

-- Create Favorite_Exercises table
CREATE TABLE IF NOT EXISTS Favorite_Exercises (
    user_id VARCHAR(255),
    exercise_name VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (exercise_name) REFERENCES Exercises(exercise_name),
    PRIMARY KEY (user_id, exercise_name) -- Composite primary key
);

-- Create friends table
CREATE TABLE IF NOT EXISTS friends (
    user_id1 VARCHAR(255),
    user_id2 VARCHAR(255),
    FOREIGN KEY (user_id1) REFERENCES Users(user_id),
    FOREIGN KEY (user_id2) REFERENCES Users(user_id),
    PRIMARY KEY (user_id1, user_id2) -- Composite primary key
);

-- Create friend_requests table
CREATE TABLE IF NOT EXISTS friend_requests (
    user_id1 VARCHAR(255),
    user_id2 VARCHAR(255),
    FOREIGN KEY (user_id1) REFERENCES Users(user_id),
    FOREIGN KEY (user_id2) REFERENCES Users(user_id),
    PRIMARY KEY (user_id1, user_id2) -- Composite primary key
);

-- Populate sample data into Users table
INSERT INTO Users (user_id, password, name, DOB, squat_max, bench_max, dl_max) 
VALUES 
('1', 'password1', 'John Doe', '1990-05-15', 200, 180, 250),
('2', 'password2', 'Jane Smith', '1992-09-20', 185, 160, 220),
('3', 'password3', 'Alice Johnson', '1985-02-10', 220, 200, 280);

-- Populate sample data into Exercises table
INSERT INTO Exercises (exercise_name, muscle, description) 
VALUES 
('Squat', 'Quadriceps, Glutes, Hamstrings', 'The squat is a compound exercise that primarily targets the quadriceps, glutes, and hamstrings. It involves lowering the body into a seated position by bending the knees and hips, then returning to a standing position.'),
('Bench Press', 'Pectorals, Deltoids, Triceps', 'The bench press is a compound exercise that primarily targets the pectoral muscles, deltoids, and triceps. It involves lying on a flat bench and pushing a weighted barbell away from the chest.'),
('Deadlift', 'Hamstrings, Glutes, Lower Back', 'The deadlift is a compound exercise that primarily targets the hamstrings, glutes, and lower back. It involves lifting a weighted barbell or dumbbells from the ground to a standing position, using a hip-hinge movement pattern.'),
('Lat Pulldown', 'Latissimus Dorsi, Biceps', 'The lat pulldown is a compound exercise that targets the latissimus dorsi muscles and biceps. It involves pulling a weighted bar down towards the chest while seated, mimicking the motion of a pull-up.'),
('Pull Ups', 'Latissimus Dorsi, Biceps', 'Pull-ups are a compound bodyweight exercise that primarily target the latissimus dorsi muscles and biceps. They involve pulling oneself up towards a bar from a hanging position.'),
('Barbell Row', 'Latissimus Dorsi, Trapezius, Biceps', 'The barbell row is a compound exercise that targets the latissimus dorsi, trapezius, and biceps. It involves pulling a weighted barbell towards the torso while standing bent over at the waist.'),
('Lat Pullover', 'Latissimus Dorsi, Pectorals, Triceps', 'The lat pullover is an isolation exercise that primarily targets the latissimus dorsi, pectorals, and triceps. It involves pulling a dumbbell or barbell over and behind the head while lying on a bench.'),
('Rear Delt Flies', 'Posterior Deltoids, Rhomboids', 'Rear delt flies are an isolation exercise that primarily target the posterior deltoids and rhomboids. They involve lifting dumbbells or cables out to the sides while bent over at the waist.'),
('Incline Bicep Curls', 'Biceps, Brachialis', 'Incline bicep curls are an isolation exercise that primarily target the biceps and brachialis. They involve curling dumbbells or a barbell while seated or lying on an incline bench.'),
('Hammer Curls', 'Biceps, Brachioradialis', 'Hammer curls are an isolation exercise that primarily target the biceps and brachioradialis. They involve curling weighted dumbbells or a barbell with a neutral grip, mimicking the motion of holding a hammer.'),
('Preacher Curls', 'Biceps', 'Preacher curls are an isolation exercise that primarily target the biceps. They involve curling a barbell or dumbbells while seated with the upper arms supported on a preacher bench.'),
('Incline Dumbbell Bench Press', 'Pectorals, Anterior Deltoids, Triceps', 'The incline dumbbell bench press is a compound exercise that targets the upper portion of the pectorals, anterior deltoids, and triceps. It involves pressing dumbbells upwards while lying on an incline bench.'),
('Chest Flies', 'Pectorals', 'Chest flies are an isolation exercise that primarily target the pectoral muscles. They involve bringing dumbbells or cables together in front of the chest in a controlled motion.'),
('Lateral Raises', 'Lateral Deltoids', 'Lateral raises are an isolation exercise that primarily target the lateral deltoid muscles. They involve lifting dumbbells out to the sides until the arms are parallel to the ground.'),
('Shoulder Press', 'Deltoids, Triceps', 'The shoulder press is a compound exercise that primarily targets the deltoid muscles and triceps. It involves pressing a weighted barbell or dumbbells overhead while standing or seated.'),
('Tricep Pushdown', 'Triceps', 'Tricep pushdowns are an isolation exercise that primarily target the triceps. They involve pushing down a weighted cable attachment using a rope, bar, or V-handle while standing.'),
('Tricep Skullcrushers', 'Triceps', 'Tricep skullcrushers are an isolation exercise that primarily target the triceps. They involve lowering a weighted barbell or dumbbells towards the forehead while lying on a bench, then extending the arms upwards.'),
('Leg Press', 'Quadriceps, Glutes, Hamstrings', 'The leg press is a compound exercise that primarily targets the quadriceps, glutes, and hamstrings. It involves pushing a weighted sled upwards using the legs while seated.'),
('Quad Extension', 'Quadriceps', 'Quad extensions are an isolation exercise that primarily target the quadriceps. They involve extending the legs against resistance while seated in a leg extension machine.'),
('Romanian Deadlifts', 'Hamstrings, Glutes, Lower Back', 'Romanian deadlifts are a compound exercise that primarily target the hamstrings, glutes, and lower back. They involve bending forward at the hips while holding a weighted barbell or dumbbells, then returning to a standing position.'),
('Hip Thrusts', 'Glutes, Hamstrings', 'Hip thrusts are a compound exercise that primarily target the glutes and hamstrings. They involve thrusting the hips upwards while seated with the upper back resting on a bench, typically with a barbell across the hips.'),
('Calf Raises', 'Calves', 'Calf raises are an isolation exercise that primarily target the calf muscles. They involve raising the heels upwards while standing on the balls of the feet, typically with the assistance of a calf raise machine or platform.');

-- Populate sample data into Exercise_History table
INSERT INTO Exercise_History (user_id, exercise, date, set_number, weight, reps) 
VALUES 
('1', 'Squat', '2024-03-18', 1, 315, 8),
('1', 'Squat', '2024-03-18', 2, 300, 8),
('1', 'Squat', '2024-03-18', 3, 275, 6),
('2', 'Bench Press', '2024-03-18', 1, 225, 10),
('2', 'Bench Press', '2024-03-18', 2, 215, 10),
('2', 'Bench Press', '2024-03-18', 3, 205, 10),
('3', 'Deadlift', '2024-03-19', 1, 405, 5),
('3', 'Deadlift', '2024-03-19', 2, 385, 6),
('3', 'Deadlift', '2024-03-19', 3, 365, 7);


-- Populate sample data into Body_Weight_History table
INSERT INTO Body_Weight_History (user_id, date, weight) 
VALUES 
('1', '2024-03-01', 130),
('2', '2024-03-01', 200),
('3', '2024-03-01', 160),
('1', '2024-03-08', 135),
('2', '2024-03-08', 195),
('3', '2024-03-08', 165),
('1', '2024-03-15', 140),
('2', '2024-03-15', 190),
('3', '2024-03-15', 170),
('1', '2024-03-22', 145),
('2', '2024-03-22', 185),
('3', '2024-03-22', 160),
('1', '2024-03-29', 150),
('2', '2024-03-29', 180),
('3', '2024-03-29', 165);

-- Populate sample data into Goals table
INSERT INTO Goals (goal_type, exercise) 
VALUES 
('Strength', 'Squat'),
('Endurance', 'Bench Press'),
('Flexibility', 'Deadlift');


-- Create Stored Procedure to calculate average calories consumed per day by a user within a date range which will be made to a graph 
DELIMITER //

CREATE PROCEDURE IF NOT EXISTS CalculateAverageCaloriesPerDay(
    IN userId INT,
    IN startDate DATE,
    IN endDate DATE
)
BEGIN
    SELECT date, SUM(calories) AS calories
    FROM Meal_History
    WHERE user_id = userId AND date BETWEEN startDate AND endDate
    GROUP BY date;
END//

DELIMITER ;
