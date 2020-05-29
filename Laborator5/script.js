var board;

const humanPlayer = '0';
const aiPlayer = 'X';
const winCombinations = [
	//rows
	[0, 1, 2], 
	[3, 4, 5],
	[6, 7, 8],
	//collumns
	[0, 3, 6],
	[1, 4, 7],
	[2, 5, 8], 
	//diagonals
	[0, 4, 8],
	[6, 4, 2]
]

//get elements with class "cell" from .html
const cells = document.querySelectorAll('.cell');
startGame();

function startGame() {
	document.querySelector(".endgame").style.display = "none";
	board = Array.from(Array(9).keys());
	//console.log(board);
	for (var i = 0; i < cells.length; i++) {
		cells[i].innerText = '';
		cells[i].style.removeProperty('background-color');
		cells[i].addEventListener('click', turnClick, false);
	}
}

function turnClick(square) {
	if (typeof board[square.target.id] == 'number') {
		//neither the human, nor the ai has placed in that spot
		//sqare.target.id gives the id of the clicked cell
		turn(square.target.id, humanPlayer)

		//the computer should move only if there are cells empty (aka no tie)
		if (!checkTie())
			turn(bestSpot(), aiPlayer);
	}
}

function turn(squareId, player) {
	//add in array
	board[squareId] = player;
	//add in html
	document.getElementById(squareId).innerText = player;

	let gameWon = checkWin(board, player);

	//if we found a winner -> is game over
	if (gameWon) gameOver(gameWon)
}


function checkWin(board, player) {
	//find all cells in the board that have been "played" by the given player
	let plays = board.reduce((a, e, i) =>
		(e === player) ? a.concat(i) : a, []);
	let gameWon = null;
	//we check winCombinations to see if the player was a win spot
	for (let [index, win] of winCombinations.entries()) {
		if (win.every(elem => plays.indexOf(elem) > -1)) {
			//we know here where the player won (at which index of winCombinations)
			gameWon = {index: index, player: player};
			break;
		}
	}
	return gameWon;
}

function gameOver(gameWon) {
	//add background color for the winner at the given index
	for (let index of winCombinations[gameWon.index]) {
		document.getElementById(index).style.backgroundColor = 
			gameWon.player == humanPlayer ? "blue" : "red";
	}

	for (var i = 0; i < cells.length; i++) {
		//when is game over, we should not be able to click on any cell
		cells[i].removeEventListener('click', turnClick, false);
	}

	//when is game over, we should declare a winner
	declareWinner(gameWon.player == humanPlayer ? "You win!" : "You lose!");
}

function declareWinner(who) {
	document.querySelector(".endgame").style.display = "block"; //from none to block
	document.querySelector(".endgame .text").innerText = who;
}

function bestSpot() {
	//it gives the first element from the empty sqares
	return emptySquares()[0];
}

function emptySquares() {
	//if an cell in the board has an X or O, it means it is non empty
	//non empty means that there are still the numbers
	return board.filter(s => typeof s == 'number');
}

function checkTie() {
	if (emptySquares().length == 0) {
		//every square is filled up -> nobody won
		for (var i = 0; i < cells.length; i++) {
			cells[i].style.backgroundColor = "green";
			cells[i].removeEventListener('click', turnClick, false);
		}
		declareWinner("Tie Game!")
		return true;
	}
	return false;
}


