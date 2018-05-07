<?php

use Illuminate\Http\Request;
use App\User;
use App\game;
use App\pieces;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', function(Request $request){
	if(Auth::attempt(['email' => $request['email'], 'password' => $request['password']])){
           $user = Auth::user();
           $user->token();
           $status = 1;
           return response()->json([
           		'status' => $status,
                'token' => $user['tokensuelo']
			]);
    }
    else{
       return response()->json(['error'=>'Unauthorised'], 401);
    }
});


Route::post('/logout', function(Request $request){ 		
    $credentials = $request->only('email');
    $user = \Auth::User();
    $isok = User::where('email', $credentials['email'])->update(['tokensuelo' => 0]);
    return response()->json([
       'isok' => $isok
	]);
});

Route::post('/buscarJugadores',function(Request $request){
	/*Printar los jugadores en pantalla para elegir con quien jugar :d */
	$playerLogged = $request->only('email');
	$players = User::where('status',0)->get();

	return response()->json([
		'playerLogged' => $playerLogged,
		'players' => $players
	]);
});

Route::post('/iniciarPartida', function(Request $request){
	/* entrada = usuario actual, usuario al que se ha invitado (del local storage = invitado ), info de la partida*/
	/* get las partidas con usuario = AuthUser*/
	/* si una de esas partidas tiene de jugador 1 o 2 al invitado, es que existe partida, ergo se carga esa partida*/
	/* si no existe una partida con el invitado, se inicia una partida con valores base */
	$credentials = $request->only('email');
    $user = User::where('email', $credentials['email'])->get()[0];
    $dataBaseRequest1 = game::where('player1',$user['id'])->first();
    $dataBaseRequest2 = game::where('player2',$user['id'])->first();
    if ( $dataBaseRequest1 == null && $dataBaseRequest2 == null){

    	$game = new game;
    	$game['player1'] = $user['id'];
    	$game['player2'] = $request['invitado'];
    	$game['turn'] = 2;
        $game->save();

    	$templatePiezas = ['0'=>['game'=> $game->id , 'color'=> '2' , 'column' => 5 , 'row' => 1 ],'1'=>['game'=> $game->id , 'color'=> '1' , 'column' => 5 , 'row' => 8 ]];

    	$pieces = new Pieces;
    	$pieces['game'] = $templatePiezas['0']['game'];
    	$pieces['color'] = $templatePiezas['0']['color'];
    	$pieces['column'] = $templatePiezas['0']['column'];
    	$pieces['row'] = $templatePiezas['0']['row'];
    	$pieces->save();
    	$pieces2 = new Pieces;
    	$pieces2['game'] = $templatePiezas['1']['game'];
    	$pieces2['color'] = $templatePiezas['1']['color'];
    	$pieces2['column'] = $templatePiezas['1']['column'];
    	$pieces2['row'] = $templatePiezas['1']['row'];
    	$pieces2->save();

    	return response()->json([
    		'game' => $game,
    		'pieces' => $pieces,
    		'pieces2' => $pieces2,
    	]);

    }else{
    	if ($dataBaseRequest1 == null){
    		$game = $dataBaseRequest2;

    	}else if ($dataBaseRequest2 == null){
    		$game = $dataBaseRequest1;
    	}
    	$pieces = pieces::where('game',$game->id)->where('color',2)->get();
    	$pieces2 = pieces::where('game',$game->id)->where('color',1)->get();
    	return response()->json([
	    	'game' => $game,
	    	'pieces' => $pieces,
	    	'pieces2' => $pieces2,
    	]);
    }
});

Route::get('/mover', function($id1, $id2) {
	/*Mover una ficha de id1 a id2, si es legal*/
});

Route::get('/consulta', function(){
	/*consulta de las personas conectadas*/
});

/*Required Parameters

Route::get('user/{id}', function ($id) {
    return 'User '.$id;
});

Route::get('posts/{post}/comments/{comment}', function ($postId, $commentId) {
    //
});
*/