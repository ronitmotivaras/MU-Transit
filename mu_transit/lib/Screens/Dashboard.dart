import 'package:flutter/material.dart';
import 'BusLocationScreen.dart'; // Import BusLocationScreen
import 'LostAndFoundScreen.dart'; // Import LostAndFoundScreen
import 'Notification.dart'; // Import Notification screen
import 'Profile.dart';
import 'SeatAvailbelScreen.dart';
import 'UserDetailsScreen.dart';

class DashboardScreen extends StatefulWidget {
  const DashboardScreen({super.key});

  @override
  State<DashboardScreen> createState() => _DashboardScreenState();
}

class _DashboardScreenState extends State<DashboardScreen> {
  int _selectedIndex = 0;

  // List of screens corresponding to each tab
  final List<Widget> _screens = [
    const UserDetailScreen(), // Navigate to User Detail Screen
    const BusLocationScrenn(), // Navigate to Bus Location Screen
    const SeatAvailabelScreen(), // Navigate to Seat Availability Screen
    const LostAndFoundScreen(), // Navigate to Lost & Found Screen
  ];

  void _onItemTapped(int index) {
    setState(() {
      _selectedIndex = index;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: const Color(0xFF008D62),
        title: const Padding(
          padding: EdgeInsets.only(left: 8.0),
          child: Text(
            "MU Transit",
            style: TextStyle(color: Colors.white),
          ),
        ),
        actions: [
          IconButton(
            icon: const Icon(Icons.notifications, color: Colors.white),
            onPressed: () {
              // Navigate to NotificationScreen
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => const NotificationScreen()),
              );
            },
          ),
          const SizedBox(width: 16),
          IconButton(
            icon: const Icon(Icons.account_circle, color: Colors.white),
            onPressed: () {
              // Navigate to ProfileScreen
              Navigator.push(
                context,
                MaterialPageRoute(builder: (context) => const ProfileScreen()),
              );
            },
          ),
          const SizedBox(width: 8),
        ],
      ),
      body: _screens[_selectedIndex], // Display selected screen

      // Enhanced Bottom Navigation Bar
      bottomNavigationBar: BottomNavigationBar(
        backgroundColor: Colors.white,
        currentIndex: _selectedIndex,
        onTap: _onItemTapped,
        selectedItemColor: Colors.green, // Green color for active item
        unselectedItemColor: Colors.grey, // Grey for inactive items
        showSelectedLabels: true, // Show labels for selected items
        showUnselectedLabels: true, // Show labels for unselected items
        iconSize: 30, // Increase icon size for better visibility
        items: const [
          BottomNavigationBarItem(
            icon: Icon(Icons.account_circle), // User icon
            label: 'User Details',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.directions_bus), // Bus icon
            label: 'Bus Location',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.chair), // Seat icon
            label: 'Seat Available',
          ),
          BottomNavigationBarItem(
            icon: Icon(Icons.search), // Lost & Found icon
            label: 'Lost & Found',
          ),
        ],
        selectedLabelStyle: const TextStyle(
          fontWeight: FontWeight.bold,
          fontSize: 14,
          color: Colors.green, // Selected label in green
        ),
        unselectedLabelStyle: const TextStyle(
          fontSize: 12,
          color: Colors.grey, // Unselected label in grey
        ),
        type: BottomNavigationBarType.fixed, // Make sure all items are visible
        elevation: 10, // Shadow effect to make it pop
      ),
    );
  }
}
