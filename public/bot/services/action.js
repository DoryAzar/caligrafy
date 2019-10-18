function execute(contextInput)
{
    var response = {"text": null , "variables": null};
    switch(contextInput.action) {
        case 'timeoff':
            response.text = "I requested  " + contextInput.paycode + " time from " + contextInput.date + " to " + (contextInput.end_date? contextInput.end_date : contextInput.date) ; 
            response.variables = {"action": null, "date": null, "end_date": null, "paycode": null};
            break;
        case 'cover':
            response.text = "Your shift on " + contextInput.date + " from " + contextInput.start_time + " to " + contextInput.end_time + " in " + contextInput['location'] + " has been covered. Rest Well!"; 
            response.variables = {"action": null, "date": null, "start_time": null, "end_time": null, "location": null};
            break;
        default:
            console.log(contextInput);
    }
    return response;
    
}