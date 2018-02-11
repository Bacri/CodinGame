<?php
#Data initialisation ###########################################
fscanf(STDIN, "%d %d %d",
    $L,//number of places
    $C,//number of rides per day
    $N//nuber of groups of people
);

$groups=new SplQueue();

for ($i = 0; $i < $N; $i++)
{
    fscanf(STDIN, "%d",
        $Pi
    );
    $groups[]=$Pi;
}
$dirhamPerRide=[];
$dirhamTotal=0;
#Calculation of dirhams per ride cycle, and patern rocording ####

for ($rideNb = 1; $rideNb <= $C; $rideNb++)
{
    $emptySeats=$L;
    $groupsPerRide=$N;//chek if all of the people are on the train
    $pattern[$rideNb]=serialize($groups);//recording queue state in array, for comparison
    do {
        $chek=$groups->bottom();
        if ($emptySeats>=$chek) {
            $groups->enqueue($groups->dequeue());
            $emptySeats-=$chek;
            $groupsPerRide--;
        }
        else {
           break; 
        }
    } while(($emptySeats>0)&&($groupsPerRide>0));
    $dirhamPerRide[$rideNb]=$L-$emptySeats;
    //echo "Ride number $rideNb earned: $dirhamPerRide[$rideNb].\n";
    
    #Pattern Search############################################   
        
    $start=array_search(serialize($groups), $pattern);    
    if ($start!==false){
        //echo "Start is $start, and end is $rideNb\n";
        $patternRound=($rideNb-$start+1);//lenght of pattern, from start to end
        $patternDirham=array_sum(array_slice($dirhamPerRide,$start-1,$patternRound));//dirhams per 1 pattern
        $rideNb=$C-($C-$start+1)%$patternRound;//skip ride iterator to after patten completion
        $dirhamTotal+=$patternDirham*(intdiv($C-$start+1,$patternRound)-1);      
    }    
}

$dirhamTotal+=array_sum($dirhamPerRide);
echo $dirhamTotal;
?>