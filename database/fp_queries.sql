

/*MATCH ALL TABLES AND GIVE RESULT FOR AN ASKED ROOT*/

SELECT 
	
	route.ID AS routeID,
	route.name AS routeName,
	
	bus.ID AS busID,
	bus.model AS busModel, 
	bus.numberOfSeats AS numberOfSeats,
	-- route.freeSeats,
	
	sr1.stationID AS stationIdFrom, 
	station1.name as stationNameFrom,    
	
	sr2.stationID AS stationIdTo, 
	station2.name as stationNameTo,
	
	/*
	sr1.routeID AS StationFromRouteId,
	sr2.routeID AS StationToRouteId,
	*/
	
	route_station_details.departure AS departure,
	route_station_details.arrival AS arrival,
	
	route_station_details.price AS price 
	
	
FROM `route_station_order` sr1


INNER JOIN route_station_order sr2 ON sr1.routeID = sr2.routeID 
INNER JOIN station station1 ON sr1.stationID = station1.ID 
INNER JOIN station station2 ON sr2.stationID = station2.ID 
INNER JOIN route_station_details ON sr1.stationID = route_station_details.stationFromID 
INNER JOIN route ON route_station_details.routeID = route.ID
INNER JOIN bus ON route.busID = bus.ID 

AND sr2.stationID = route_station_details.stationToID

AND sr1.routeID = route_station_details.routeID
AND sr2.routeID = route_station_details.routeID

/*
AND sr1.stationID = 1
AND sr2.stationID = 5
AND sr2.stationOrder > sr1.stationOrder
AND DATE( route_station_details.departure ) = '2014-02-18'*/





/*GIVE ME ALL STATIONS FROM ALL THE CITYS*/

SELECT 
city.ID AS cityID, 
city.name AS cityName,
station.id AS stationID,
station.name AS stationName

FROM station

INNER JOIN city ON city.ID = station.cityID


/*GIVE ME ALL THE STATIONS OF ANY ROOT*/

SELECT 

route.ID AS routeID,
route.name AS routeName,
route_station_order.stationOrder,
station.ID AS stationID,
station.name AS stationName


FROM route_station_order

JOIN route ON route_station_order.routeID = route.ID
JOIN station ON route_station_order.stationID = station.ID
ORDER BY route_station_order.stationOrder


/*GIVE ALL THE ROOTS*/

SELECT 

route.name AS routeName,
bus.model as busModel,
bus.numberOfSeats AS busSeats,
route.availableSeats

FROM route

JOIN bus ON route.busID = bus.ID


/*GIVE ME DETAILS FOR ALL THE ROOTS AND THEIR STATIONS*/

SELECT
routeID,
route.name AS routeName,
route_station_details.ID AS routeStationDetailsID,


stationFrom.name AS stationNameFrom,
stationTo.name AS stationNameTo,
price,
departure,
arrival,
freeSeats,
reservedSeats

FROM route_station_details 

INNER JOIN route ON route_station_details.routeID = route.ID
INNER JOIN bus ON route.busID = bus.ID
INNER JOIN station stationFrom ON route_station_details.stationFromID = stationFrom.ID
INNER JOIN station stationTo ON route_station_details.stationToID = stationTo.ID

------------------------------------------------------------
-------------------DATABASE CHANGED-----------------------------------------


SELECT 

route.ID as routeID,

station1.name as firstStation,
station2.name as lastStation,

route.numberOfStations

FROM route

JOIN station station1 ON route.firstStationID = station1.ID 
JOIN station station2 ON route.lastStationID = station2.ID 

---------------------------------------------------------------------------
SELECT  
	bus.ID,
	bus.code,
	busmodel.model , 
	busmodel.numberOfSeats  
	
	FROM bus
	
	join busmodel ON bus.busModelID = busmodel.id
	ORDER BY model ASC

------------------------------------------------------------------------------
SELECT
				route_station_details.routeID,
				route.name AS routeName,
				route_station_details.ID AS routeStationDetailsID,
				
				unique_route.active,
				unique_route.busID,
				unique_route.ID as uniqueRouteID,
				
				stationFrom.name AS stationNameFrom,
				stationTo.name AS stationNameTo,
				route_station_details.price,
				YEAR(route_station_details.departure) as yearDeparture,
				MONTH(route_station_details.departure) as monthDeparture,
				DAYOFMONTH(route_station_details.departure) as dayDeparture,
				HOUR(route_station_details.departure) AS hourDeparture,
				MINUTE(route_station_details.departure) AS minuteDeparture,
				
				YEAR(route_station_details.arrival) as yearArrival,
				MONTH(route_station_details.arrival) as monthArrival,
				DAYOFMONTH(route_station_details.arrival) as dayArrival,
				HOUR(route_station_details.arrival) AS hourArrival,
				MINUTE(route_station_details.arrival) AS minuteArrival,
				
				route_station_details.arrival,
				route_station_details.freeSeats,
				route_station_details.reservedSeats
				

				FROM route_station_details 

				INNER JOIN unique_route ON route_station_details.uniqueRouteID = unique_route.ID
				INNER JOIN route ON route_station_details.routeID = route.ID
				
				INNER JOIN station stationFrom ON route_station_details.stationFromID = stationFrom.ID
				INNER JOIN station stationTo ON route_station_details.stationToID = stationTo.ID
				WHERE route_station_details.routeID = :routeID

-------------------------------------------------------------------------------------

--FROM

SELECT  

route_station_details.stationFromID, 

station.name 

FROM route_station_details

JOIN station on route_station_details.stationFromID = station.ID

ORDER BY station.name


--TO

SELECT DISTINCT 

route_station_details.stationToID, 

station.name 

FROM route_station_details

JOIN station on route_station_details.stationToID = station.ID

WHERE route_station_details.stationFromID=2 


--------------------------------------------------------------------------


SELECT
					
					route_station_details.routeID,
					route.name AS routeName,
					route_station_details.ID AS routeStationDetailsID,
					
					unique_route.active,
					
					unique_route.busID,
					busmodel.model AS busModel,
					busmodel.numberOfSeats AS busSeats,
					bus.code AS busCode,
					
					unique_route.ID as uniqueRouteID,
					
					route_station_details.stationFromID,
					route_station_details.stationToID,
					
					stationFrom.name AS stationNameFrom,
					stationTo.name AS stationNameTo,
					
					route_station_details.price,
					
					
					CONCAT(DAYOFMONTH(route_station_details.departure), '-',MONTH(route_station_details.departure),'-',YEAR(route_station_details.departure)) AS departure,
					CONCAT(DAYOFMONTH(route_station_details.arrival), '-',MONTH(route_station_details.arrival),'-',YEAR(route_station_details.arrival)) AS arrival,
					
					
					route_station_details.freeSeats,
					route_station_details.reservedSeats
					

					FROM route_station_details 

					JOIN unique_route ON route_station_details.uniqueRouteID = unique_route.ID
					JOIN route ON route_station_details.routeID = route.ID
					
					JOIN station stationFrom ON route_station_details.stationFromID = stationFrom.ID
					JOIN station stationTo ON route_station_details.stationToID = stationTo.ID
					JOIN bus ON unique_route.busID = bus.ID
					JOIN busmodel ON busmodel.ID = bus.busModelID
