import 'package:flutter/material.dart';

class UserDetailScreen extends StatefulWidget {
  const UserDetailScreen({super.key});

  @override
  State<UserDetailScreen> createState() => _UserDetailScreenState();
}

class _UserDetailScreenState extends State<UserDetailScreen> {
  // Dummy data for the User Detail (Data coming from DB)
  final String pickupRoute = "Route A";
  final String pickupTime = "08:00 AM";
  final String pickupBusNo = "Bus 12";
  final String pickupDriverName = "John Doe";
  final String pickupDriverPhone = "+1234567890";
  final String pickupShift = "1st Shift (Pick-Up)";  // Shift data for Pickup

  final String dropRoute = "Route B";
  final String dropTime = "05:00 PM";
  final String dropBusNo = "Bus 14";
  final String dropDriverName = "Jane Smith";
  final String dropDriverPhone = "+0987654321";
  final String dropShift = "1st Shift (Drop)";  // Shift data for Drop

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SingleChildScrollView(
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const Center(
                child: Text(
                  "User Details",
                  style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
                ),
              ),
              const SizedBox(height: 20),

              // Pick-Up Route Details
              _buildRouteDetails("Pick-Up Route", pickupRoute, pickupTime, pickupBusNo, pickupDriverName, pickupDriverPhone, pickupShift),
              const SizedBox(height: 20),

              // Drop-Route Details
              _buildRouteDetails("Drop Route", dropRoute, dropTime, dropBusNo, dropDriverName, dropDriverPhone, dropShift),
            ],
          ),
        ),
      ),
    );
  }

  // Helper widget to display route details
  Widget _buildRouteDetails(
      String routeType,
      String route,
      String time,
      String busNo,
      String driverName,
      String driverPhone,
      String shift // New parameter for Shift info
      ) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(12),
        boxShadow: [
          BoxShadow(
            color: Colors.black12,
            blurRadius: 8,
            spreadRadius: 2,
            offset: const Offset(0, 4),
          ),
        ],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            "$routeType: $route",
            style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 8),
          _buildDetailRow("Time:", time),
          _buildDetailRow("Shift:", shift),  // Display shift info after Time
          _buildDetailRow("Bus No.:", busNo),
          _buildDetailRow("Driver Name:", driverName),
          _buildDetailRow("Driver Phone:", driverPhone),
        ],
      ),
    );
  }

  // Helper method to build each row of details
  Widget _buildDetailRow(String label, String value) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 6),
      child: Row(
        children: [
          Text(
            "$label ",
            style: const TextStyle(
              fontSize: 16,
              fontWeight: FontWeight.bold,
              color: Colors.black54,
            ),
          ),
          Expanded(
            child: Text(
              value,
              style: const TextStyle(fontSize: 16, color: Colors.black),
            ),
          ),
        ],
      ),
    );
  }
}
