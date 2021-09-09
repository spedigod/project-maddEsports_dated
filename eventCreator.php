<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create new event</title>
</head>
<body>
    <form action="includeFiles/eventCreator.inc.php" method="POST" enctype="multipart/form-data">
        <label for="eventTitle">Title</label><br />
        <input type="text" name="eventTitle" id="eventTitle" title="eventTitle"><br /><br />

        <label for="eventGame">Game</label><br />
        <select name="eventGame" id="eventGame">
            <option value="LeagueOfLegends">LeagueOfLegends</option>
            <option value="RainbowSixSiege">RainbowSixSiege</option>
            <option value="Overwatch">Overwatch</option>
            <option value="Dota2">Dota2</option>
            <option value="CounterStrike1.6">CounterStrike1.6</option>
            <option value="CounterStrikeGO">CounterStrikeGO</option>
            <option value="Valorant">Valorant</option>
        </select><br /> <br />


        <label for="eventBanner">Title banner (format: jpg, jpeg, png)</label><br />
        <input type="file" name="eventBanner" id="eventBanner"><br /><br />

        <label for="eventSmallDescription">Small description</label><br />
        <input type="text" name="eventSmallDescription" id="eventSmallDescription" title="eventSmallDescription"><br /><br />

        <label for="eventDescription">Description (format: txt-HTML)</label><br />
        <input type="file" name="eventDescription" id="eventDescription" title="eventDescription"><br /><br />

        <label for="eventBackground"></label>Background (format: jpg, jpeg, png)</label><br />
        <input type="file" name="eventBackground" id="eventBackground"><br /><br />

        <label for="eventStart">Date of start (YYYY-MM-DD hh:mm)</label><br />
        <input type="text" name="eventStart" id="eventStart"><br /><br />

        <label for="checkIn">Check-In (YYYY-MM-DD hh:mm)</label><br />
        <select name="checkIn" id="checkIn">
        <option value="0mins">On Event Start</option>
        <option value="15mins" selected="selected">15 Minutes before Start</option>
        <option value="30mins">30 Minutes before Start</option>
        <option value="45mins">45 Minutes before Start</option>
        <option value="1hour">1 Hour before Start</option>
        </select><br /><br />

        <label for="eventPricing">Prize pool (Pricing in USD)</label><br />
        <input type="number" name="prizePool" id="prizePool"><br /><br />
        <br />

        <label for="eventSettings">Settings (format: txt-HTML)</label><br />
        <input type="file" name="eventSettings" id="eventSettings" title="eventSettings"><br /><br />

        <input type="hidden" name="creatorID" id="creatorID" value="<?=$_SESSION['user_id']?>"><br /><br />
        <button type="submit" name="createEvent" id="createEvent">Create Event</button>
    </form>
</body>
</html>