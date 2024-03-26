CREATE TABLE users 
(
    username VARCHAR(50) PRIMARY KEY,
    password VARCHAR(255) NOT NULL,
    fname VARCHAR(255) NOT NULL,
    lname VARCHAR(255) NOT NULL,
    addr1 VARCHAR(255) NOT NULL,
    addr2 VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    telephone BIGINT NOT NULL,
    mobile BIGINT NOT NULL
);

CREATE TABLE category
(
    CategoryID INT PRIMARY KEY AUTO_INCREMENT,
    CategoryDepartment VARCHAR(255) NOT NULL
);

CREATE TABLE books
(
    ISBN VARCHAR(50) PRIMARY KEY,
    bookTitle VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    edition INT NOT NULL,
    year INT NOT NULL,
    CategoryID INT,
    reserved BOOLEAN NOT NULL,
    FOREIGN KEY (CategoryID) REFERENCES category(CategoryID)
);

CREATE TABLE reservations
(
    ISBN VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL,
    reservedDate DATE NOT NULL, -- Added reservedDate field
    PRIMARY KEY (ISBN, username), -- Combined primary key for the reservations table
    FOREIGN KEY (ISBN) REFERENCES books(ISBN),
    FOREIGN KEY (username) REFERENCES users(username)
);

INSERT INTO users (username, password, fname, lname, addr1, addr2, city, telephone, mobile)
VALUES 
   ('alanjmckenna', 't1234s', 'Alan', 'McKenna', '38 Cranley Road', 'Fairview', 'Dublin', 99983777, 856625567),
   ('joecrotty', 'kj7899', 'Joseph', 'Crotty', 'Apt 5 Clyde Road', 'Donnybrook', 'Dublin', 8887889, 876654456),
   ('tommy100', '123456', 'Tom', 'Behan', '14 Hyde Road', 'Dalkey', 'Dublin', 9983747, 876738782);

INSERT INTO category (CategoryDepartment)
VALUES 
   ('Health'),
   ('Business'),
   ('Biography'),
   ('Technology'),
   ('Travel'),
   ('Self-Help'),
   ('Cookery'),
   ('Fiction');

INSERT INTO books (ISBN, bookTitle, author, edition, year, CategoryID, reserved)
VALUES 
   ('093-403992', 'Computers in Business', 'Alicia Oneill', 3, 1997, 3, FALSE),
   ('23472-8729', 'Exploring Peru', 'Stephanie Birchie', 4, 2005, 5, FALSE),
   ('237-34823', 'Business Strategy', 'Joe Peppard', 2, 2002, 2, FALSE),
   ('23u8-923849','A guide to nutrition', 'John Thorpe', 2, 1997, 1, FALSE),
   ('2983-3494', 'Cooking for children', 'Anabelle Sharpe', 1, 2003, 7, FALSE),
   ('82n8-308', 'computers for idiots', 'Susan O' 'Neill', 5, 1998, 4, FALSE),
   ('9823-2403-0','My life in picture', 'Kevin Graham', 8, 2004, 1, FALSE),
   ('98234-029384', 'My ranch in Texas', 'George Bush', 1, 2005, 7, TRUE),
   ('9823-98345', 'How to cook Italian food', 'Jamie Oliver', 2, 2005, 7, TRUE),
   ('9823-98487', 'Optimising your business', 'Cleo Blair', 1, 2001, 2, FALSE),
   ('988745-234', 'Tara Road', 'Maeve Binchy', 4, 2002, 8, FALSE),
   ('993-004-00', 'My life in bits', 'John Smith', 1, 2001, 1, FALSE),
   ('9987-0039882','Shooting History', 'Jon Snow', 1, 2003, 1, FALSE);
   
INSERT INTO reservations (ISBN, username, reservedDate)
VALUES 
   ('98234-029384', 'joecrotty', '2008-10-11'),
   ('9823-98345', 'tommy100', '2008-10-11');