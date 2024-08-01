<?php

declare(strict_types=1);
namespace Rodrom\Advent202219;

class Simulation
{
    public readonly Blueprint $blueprint;
    public readonly int $min;

    public function __construct(Blueprint $blueprint, int $min)
    {
        $this->blueprint = $blueprint;
        $this->min = $min;
    }

    public function qualityLevel(int $currentMinute, array $robots, array $resources, array $cache): int
    {
        // echo "time=$currentMinute#cache size:".count($cache)."#bots:". json_encode($robots). "#amt:" .json_encode($resources) . PHP_EOL;
        if ($currentMinute === 0) {
            // echo "  BASE CASE geodes at 0:{$resources['geodes']}\n";
            return $resources['geode'];
        }

        $key = "$currentMinute#" . implode("-", $robots) . "#" . implode("-", $resources);
        if (array_key_exists($key, $cache)) {
            // echo "  CACHE HIT: {$cache[$key]}\n";
            return $cache[$key];
        }

        $maxval = $resources['geode'] + $robots['geode'] * $currentMinute;
        // echo "  initial maxval:{$maxval}\n";
        // echo "$currentMinute: NEW FOREACH blueprint->robots\n";
        foreach ($this->blueprint->robots as $robotType => $recipe) {
            // echo "btype: $robotType # recipe:" .json_encode($recipe) . PHP_EOL;
            // if ($robotType !== 'geode') echo "{$robots[$robotType]} >= " . $this->blueprint->maxQtyResource($robotType) . PHP_EOL;
            // if ($robotType !== 'geode' && $robots[$robotType] >= $this->blueprint->maxQtyResource($robotType)) {
            if ($robotType !== 'geode'
                && $robots[$robotType] * $currentMinute >= $this->blueprint->maxQtyResource($robotType) * $currentMinute - $resources[$robotType] ) {
                // echo "    robot not builded:{$robotType}\n";
                continue;
            }

            $waitForBuilding = 0;
            $robotmade = true;
            
            foreach ($recipe as $resNeeded => $resQty) {
                if ($robots[$resNeeded] === 0) {
                    // echo "      NO ROBOT $resNeeded AVAILABLE FOR BUILD ROBOT $robotType\n";
                    $robotmade = false;
                    break;
                }
                // HACK FOR CEIL INT DIVISION. This is simply
                // ceil( ($resQty - $resources[$resNeeded]) / $robots[$resNeeded] )
                $waitToGetResources = intval(ceil(($resQty - $resources[$resNeeded]) / $robots[$resNeeded]));
                // echo "max($waitForBuilding, $waitToGetResources) for $resNeeded\n;";
                $waitForBuilding = max($waitForBuilding, $waitToGetResources);
            }
            if ($robotmade) {
                // echo "ELSE BRANCH RECIPE BUILDING: $robotType # Minute: $currentMinute  Wait: $waitForBuilding\n";
                $remainingTime = $currentMinute - $waitForBuilding - 1;
                // echo "    ROBOT CAN BE DONE. remtime = $remainingTime\n";
                if ($remainingTime <= 0) {
                    // echo "      ROBOT DONT NEED TO BE DONE AFTER LIMIT TIME\n";
                    continue;
                }
                
                $robotsFuture = $robots;
                $resFuture = $resources;
                foreach($resources as $r => $qty) {
                    $resFuture[$r] = $qty + $robots[$r] * ($waitForBuilding + 1);
                }
                
                foreach ($recipe as $rtype => $qty) {
                    $resFuture[$rtype] -= $qty;
                }

                $robotsFuture[$robotType] += 1;
                // OPTIMIZATION
                foreach ($resources as $r => $qty) {
                    $resFuture[$r] = min($resFuture[$r], $this->blueprint->maxQtyResource($r) * $remainingTime);
                }
                // echo "    ROBOT DONE TYPE: $robotType\n";
                $maxval = max($maxval, $this->qualityLevel($remainingTime, $robotsFuture, $resFuture, $cache));
                // echo "   maxval after dfs: $maxval\n";
            }
        }
        $cache[$key] = $maxval;
        // echo "CACHE UPDATED: $key #maxval: $maxval\n";
        return $maxval;
    }
}
