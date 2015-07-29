<?php

require_once("autoload.php");

use ElephantIO\Client as Elephant;
use ElephantIO\Engine\SocketIO\Version1X as Version1X;


$elephant = new Elephant(new Version1X('http://localhost:3000'));

$elephant->initialize();
$elephant->emit('next_question_get', ['leaderboard' => '<table class="table m-t-10">
                        <tr>
                            <th>Rank</th>
                            <th>Player name</th>
                            <th>Points</th>
                        </tr>
                        <tr>
                            <td>1.</td>
                            <td>Matija ParausicMatija ParausicMatija Parausic</td>
                            <td>20</td>
                        </tr>
                        <tr>
                            <td>2.</td>
                            <td>Matija Parausic</td>
                            <td>15</td>
                        </tr>
                        <tr>
                            <td>3.</td>
                            <td>Matija Parausic</td>
                            <td>10</td>
                        </tr>
                    </table>',

             'quizID' => 1,

             'question' => '<p>This is very long question. This is very long question. This is very long question. This is very long question. This is very long question. This is very long question. This is very long question. This is very long question. This is very long question. This is very long question. This is very long question.
                    </p>
                    <div class="quiz-answers m-t-20">
                        <label><input name="radio-quiz" type="radio">Option 1</label>
                        <label><input name="radio-quiz" type="radio">Option 2</label>
                        <label><input name="radio-quiz" type="radio">Option 3</label>
                    </div>',

             'seconds' => 10,
             ]
             
             );

$elephant->close();