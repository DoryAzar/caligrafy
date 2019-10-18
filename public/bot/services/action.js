function execute(contextInput)
{
    var response = null;
    switch(contextInput.action) {
        case 'timeoff':
            response = "I requested  " + contextInput.paycode + " time from " + contextInput.date + "to " + (contextInput.end_date? contextInput.end_date : contextInput.date) ; 
            break;
        case 'cover':
            response = "Your shift on " + contextInput.date + "from " + contextInput.start_date + " to " + contextInput.end_date + " in " + contextInput['location'] + " has been covered. Rest Well!"; 
            break;
        default:
            console.log(contextInput);
    }
    return response;
    
}