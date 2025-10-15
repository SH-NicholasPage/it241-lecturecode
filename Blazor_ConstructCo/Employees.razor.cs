using ConstructCo.Shared.Models.API;
using Microsoft.AspNetCore.Components;

namespace ConstructCo.Blazor.Components.Pages;

public partial class Employees : ComponentBase
{
    private readonly String API_URL = $"{Constants.API_URL}/v1/Employee";

    [Inject]
    private IHttpClientFactory HttpClientFactory { get; set; } = null!;

    private List<Employee>? Emps { get; set; }
    private bool ShowManagers { get; set; }
    private String? SuccessMessage { get; set; }
    private Employee NewEmployee { get; set; } = new();

    protected override async Task OnInitializedAsync()
    {
        await LoadEmployees();
    }

    private async Task LoadEmployees()
    {
        try
        {
            HttpClient client = HttpClientFactory.CreateClient();
            String endpoint = ShowManagers 
                ? $"{API_URL}/GetAllManagers" 
                : $"{API_URL}/GetAllEmployees";

            List<Employee>? result = await client.GetFromJsonAsync<List<Employee>>(endpoint);
            Emps = result ?? [];
        }
        catch (Exception ex)
        {
            Console.WriteLine("Error fetching employees: " + ex.Message);
        }
    }

    private async Task SaveEmployee(Employee employee)
    {
        try
        {
            HttpClient client = HttpClientFactory.CreateClient();
            HttpResponseMessage response = await client.PutAsJsonAsync($"{API_URL}/UpdateEmployee", employee);

            if (response.IsSuccessStatusCode)
            {
                SuccessMessage = $"Employee {employee.EmpNum} saved successfully.";
            }
            else
            {
                SuccessMessage = $"Error saving employee {employee.EmpNum}.";
            }
        }
        catch (Exception ex)
        {
            Console.WriteLine("Error updating employee: " + ex.Message);
        }
    }

    private async Task DeleteEmployee(uint empNum)
    {
        try
        {
            HttpClient client = HttpClientFactory.CreateClient();
            HttpResponseMessage response = await client.DeleteAsync($"{API_URL}/DeleteEmployee?empNum={empNum}");

            if (response.IsSuccessStatusCode)
            {
                SuccessMessage = $"Employee {empNum} deleted successfully.";
                Emps!.Remove(Emps.First(e => e.EmpNum == empNum));
            }
            else
            {
                SuccessMessage = $"Error deleting employee {empNum}.";
            }
        }
        catch (Exception ex)
        {
            Console.WriteLine("Error deleting employee: " + ex.Message);
        }
    }

    private async Task CreateEmployee()
    {
        try
        {
            HttpClient client = HttpClientFactory.CreateClient();
            HttpResponseMessage response = await client.PostAsJsonAsync($"{API_URL}/CreateEmployee", NewEmployee);

            if (response.IsSuccessStatusCode)
            {
                SuccessMessage = $"Employee {NewEmployee.EmpFname} {NewEmployee.EmpLname} created successfully.";
                NewEmployee = new Employee();
                await LoadEmployees();
            }
            else
            {
                SuccessMessage = "Error creating employee.";
            }
        }
        catch (Exception ex)
        {
            Console.WriteLine("Error creating employee: " + ex.Message);
        }
    }
    
    private async Task OnShowManagerChanged(ChangeEventArgs e)
    {
        ShowManagers = (bool)e.Value!;
        await LoadEmployees();
    }
}