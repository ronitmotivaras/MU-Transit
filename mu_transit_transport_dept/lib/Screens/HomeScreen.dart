import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';

import 'LocationScreen.dart';
import 'LostandFoundScreen.dart';
import 'ProfileScreen.dart';
import 'SeatAvailabelScreen.dart';

class HomeScreen extends StatefulWidget {
  const HomeScreen({super.key});

  @override
  State<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {
  int _selectedIndex = 0;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.grey[200],
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const SizedBox(height: 0),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Row(
                  children: [
                    Icon(Icons.directions_bus, color: Colors.teal, size: 28),
                    const SizedBox(width: 5),
                    const Text(
                      "MU Transit",
                      style: TextStyle(
                        fontSize: 22,
                        fontWeight: FontWeight.bold,
                        color: Colors.teal,
                      ),
                    ),
                  ],
                ),
                Row(
                  children: [
                    IconButton(
                      icon: const Icon(Icons.notifications, color: Colors.black),
                      onPressed: () {
                        // Handle notification tap (optional: navigate to NotificationScreen)
                        ScaffoldMessenger.of(context).showSnackBar(
                          const SnackBar(content: Text("No new notifications.")),
                        );
                      },
                    ),
                    const SizedBox(width: 8), // spacing between icons
                    CircleAvatar(
                      backgroundColor: Colors.grey[300],
                      child: IconButton(
                        icon: const Icon(Icons.person, color: Colors.black),
                        onPressed: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(builder: (context) => ProfileScreen()),
                          );
                        },
                      ),
                    ),
                  ],
                ),

              ],
            ),
            const SizedBox(height: 20),
            // Bus Details Card
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(12),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black12,
                    blurRadius: 6,
                    spreadRadius: 2,
                  ),
                ],
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    "Pick Up",
                    style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 10),
                  _buildBusDetail("Shift:", "1st"),
                  const SizedBox(height: 8),
                  _buildBusDetail("Pickup Time:", "8:00 AM"),
                  const SizedBox(height: 8),
                  _buildBusDetail("Bus No.:", "12"),
                  const SizedBox(height: 8),
                  _buildBusDetail("Route:", "Campus -> Main Gate -> Library"),
                  const SizedBox(height: 8),
                  _buildBusDetail("Driver Name:", "John Doe"),
                  const SizedBox(height: 8),
                  _buildBusDetail("Driver Contact:", "+91 9876543210"),
                ],
              ),
            ),

            // Add this below the Pickup Card in the build method (inside Column children):
            const SizedBox(height: 20),
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(12),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black12,
                    blurRadius: 6,
                    spreadRadius: 2,
                  ),
                ],
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text(
                    "Drop",
                    style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 10),
                  _buildBusDetail("Shift:", "1st"),
                  const SizedBox(height: 8),
                  _buildBusDetail("Drop Time:", "4:30 PM"),
                  const SizedBox(height: 8),
                  _buildBusDetail("Bus No.:", "12"),
                  const SizedBox(height: 8),
                  _buildBusDetail("Route:", "Library -> Main Gate -> Campus"),
                  const SizedBox(height: 8),
                  _buildBusDetail("Driver Name:", "John Doe"),
                  const SizedBox(height: 8),
                  _buildBusDetail("Driver Contact:", "+91 9876543210"),
                ],
              ),
            ),

            const SizedBox(height: 20),
            const Text("Cheap bus tickets", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            const SizedBox(height: 10),
            Container(
              padding: const EdgeInsets.all(16),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(12),
                boxShadow: [
                  BoxShadow(
                    color: Colors.black12,
                    blurRadius: 6,
                    spreadRadius: 2,
                  ),
                ],
              ),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: const [
                      Text("Cheapest", style: TextStyle(fontSize: 16, color: Colors.grey)),
                      SizedBox(height: 5),
                      Text("£125", style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                    ],
                  ),
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: const [
                      Text("Average", style: TextStyle(fontSize: 16, color: Colors.grey)),
                      SizedBox(height: 5),
                      Text("£146", style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
                    ],
                  ),
                  ElevatedButton(
                    onPressed: () {},
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.teal,
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(8),
                      ),
                    ),
                    child: const Text("Find ticket", style: TextStyle(color: Colors.white)),
                  ),
                ],
              ),
            ),
          ],
        ),
      ),
      bottomNavigationBar: Container(
        padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 5),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(30),
          boxShadow: [
            BoxShadow(
              color: Colors.black12,
              blurRadius: 10,
              spreadRadius: 2,
            ),
          ],
        ),
        margin: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.spaceAround,
          children: [
            _bottomNavItem(Icons.home_rounded, "Home", 0),
            _bottomNavItem(Icons.location_on, "Bus Location", 1),
            _bottomNavItem(Icons.airline_seat_recline_normal_rounded, "Seats", 2),
            _bottomNavItem(Icons.search_rounded, "Lost & Found", 3),
          ],
        ),
      ),
    );
  }

  Widget _bottomNavItem(IconData icon, String label, int index) {
    return GestureDetector(
      onTap: () {
        setState(() {
          _selectedIndex = index;
        });

        // Check if the tapped index is for "Bus Location" (index 1 in your case)
        if (index == 1) {
          // Navigate to the LocationScreen when "Bus Location" is clicked
          Navigator.push(
            context,
            MaterialPageRoute(builder: (context) => const locationScreen()),
          );
        }

        // Check if the tapped index is for "Seats" (index 2 in your case)
        if (index == 2) {
          // Navigate to the SeatAvailableScreen when "Seats" is clicked
          Navigator.push(
            context,
            MaterialPageRoute(builder: (context) => const seatAvailabelScreen()),
          );
        }

        // Check if the tapped index is for "Lost & Found" (index 3 in your case)
        if (index == 3) {
          // Navigate to the LostAndFoundScreen when "Lost & Found" is clicked
          Navigator.push(
            context,
            MaterialPageRoute(builder: (context) => const lostandFoundScreen()),
          );
        }

        // You can add more conditions for other indices as needed
      },
      child: Column(
        mainAxisSize: MainAxisSize.min,
        children: [
          Icon(icon, color: _selectedIndex == index ? Colors.black : Colors.grey),
          Text(
            label,
            style: TextStyle(
              fontSize: 12,
              color: _selectedIndex == index ? Colors.black : Colors.grey,
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildBusDetail(String label, String value) {
    return Row(
      children: [
        Text(
          label,
          style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
        ),
        const SizedBox(width: 8),
        Text(
          value,
          style: const TextStyle(fontSize: 16, color: Colors.grey),
        ),
      ],
    );
  }

  Widget _buildTextField(String text) {
    return TextField(
      decoration: InputDecoration(
        filled: true,
        fillColor: Colors.grey[100],
        prefixIcon: const Icon(Icons.location_on, color: Colors.black54),
        hintText: text,
        border: OutlineInputBorder(
          borderRadius: BorderRadius.circular(8),
          borderSide: BorderSide.none,
        ),
      ),
    );
  }
}
